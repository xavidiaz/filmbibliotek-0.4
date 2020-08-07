CREATE TABLE IF NOT EXISTS `films` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(100) NOT NULL,
    `director` varchar(100) NOT NULL,
    `category` varchar(10) NOT NULL,
    `year` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1 AUTO_INCREMENT = 4;