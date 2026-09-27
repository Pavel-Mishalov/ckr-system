-- Создание связий при вставке новой группы
DELIMITER $$
CREATE TRIGGER after_category_insert
    AFTER INSERT ON `groups`
    FOR EACH ROW
BEGIN
    -- 1. Копируем все связи родителя для нового потомка и увеличиваем depth на 1
    IF NEW.id_parent IS NOT NULL THEN
        INSERT INTO group_tree (parent_id, child_id, depth)
    SELECT parent_id, NEW.id, depth + 1
    FROM group_tree
    WHERE child_id = NEW.id_parent;
END IF;

-- 2. Добавляем обязательную связь самой с собой (глубина 0)
INSERT INTO group_tree (parent_id, child_id, depth)
VALUES (NEW.id, NEW.id, 0);
END$$
DELIMITER ;



-- При удалении группы, сработает удяление из таблицы всех связий, где на нее ссылались




-- Триггер на обновление groups вызывает ошибку при использовании связаных данных с таблицы groups
-- вот текст ошибки `Can't update table 'groups' in stored function/trigger`
-- Важно для обновлений использовать вызов процедуры как `CALL move_group(id_group, new_id_parent_group)` вместо `UPDATE groups SET id_parent = new_id_parent_group WHERE id = id_group`
DELIMITER $$
CREATE PROCEDURE move_group(
    IN p_id INT(11),          -- ID группы, которую перемещаем
    IN p_new_parent INT(11)   -- ID нового родителя (или NULL)
)
BEGIN
    -- Включаем безопасный режим транзакции: либо всё выполнится, либо ничего
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
BEGIN
ROLLBACK;
RESIGNAL;
END;

START TRANSACTION;

-- 1. Защита от перемещения в самого себя
IF p_id = p_new_parent THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Группа не может быть родителем самой себе';
END IF;

    -- 2. Защита от зацикливания дерева (нельзя перенести корень в его же подкатегорию)
    IF p_new_parent IS NOT NULL AND EXISTS (
        SELECT 1 FROM group_tree
        WHERE parent_id = p_id AND child_id = p_new_parent
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Нельзя переместить группу внутрь её собственного потомка';
END IF;

    -- 3. РАЗРЫВАЕМ старые связи с предками для перемещаемой ветки
DELETE FROM group_tree
WHERE child_id IN (
    SELECT x.id FROM (SELECT child_id AS id FROM group_tree WHERE parent_id = p_id) x
)
  AND parent_id IN (
    SELECT y.id FROM (SELECT parent_id AS id FROM group_tree WHERE child_id = p_id AND parent_id != p_id) y
);

-- 4. СТРОИМ новые связи с предками для всей ветки
IF p_new_parent IS NOT NULL THEN
        INSERT INTO group_tree (parent_id, child_id, depth)
SELECT supertree.parent_id, subtree.child_id, supertree.depth + subtree.depth + 1
FROM group_tree AS supertree
         CROSS JOIN group_tree AS subtree
WHERE supertree.child_id = p_new_parent
  AND subtree.parent_id = p_id;
END IF;

    -- 5. Обновляем parent_id в основной таблице (теперь InnoDB заблокирует только эту строку)
UPDATE `groups`
SET id_parent = p_new_parent
WHERE id = p_id;

COMMIT;
END$$
DELIMITER ;