CREATE TABLE group_tree (
    parent_id INT NOT NULL,
    child_id INT NOT NULL, -- ID потомка (сама категория или её дети)
    depth INT NOT NULL, -- Глубина (0 - связь сама с собой, 1 - прямой родитель, 2 - дедушка...)
    PRIMARY KEY (parent_id, child_id),
    FOREIGN KEY (parent_id) REFERENCES `groups` (id) ON DELETE CASCADE,
    FOREIGN KEY (child_id) REFERENCES `groups` (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO group_tree (parent_id, child_id, depth)
WITH RECURSIVE tree_path AS (
    -- Шаг 1: Связи каждой категории самой с собой (глубина 0)
    SELECT
        id AS parent_id,
        id AS child_id,
        0 AS depth
    FROM `groups`

    UNION ALL

    -- Шаг 2: Рекурсивный подъем вверх к родителям
    SELECT
        g.id_parent AS parent_id,
        tp.child_id AS child_id,
        tp.depth + 1 AS depth
    FROM `groups` g
             JOIN tree_path tp ON g.id = tp.parent_id
    WHERE g.id_parent IS NOT NULL
)
SELECT parent_id, child_id, depth
FROM tree_path;