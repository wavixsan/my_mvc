-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 14 2017 г., 18:03
-- Версия сервера: 5.5.53
-- Версия PHP: 5.6.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `my_mvc`
--

-- --------------------------------------------------------

--
-- Структура таблицы `author`
--

CREATE TABLE `author` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `book`
--

CREATE TABLE `book` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `style_id` tinyint(3) UNSIGNED DEFAULT NULL,
  `description` text,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `book`
--

INSERT INTO `book` (`id`, `title`, `price`, `style_id`, `description`, `status`) VALUES
(403, 'odio curabitur convallis duis', '961.87', 13, 'quis turpis sed ante vivamus tortor duis mattis egestas metus aenean fermentum', 1),
(404, 'sapien quis libero nullam', '700.99', 5, 'primis in faucibus orci luctus et ultrices posuere cubilia curae nulla dapibus dolor vel est donec odio justo sollicitudin ut suscipit a feugiat et eros vestibulum ac', 0),
(405, 'platea dictumst', '133.80', 2, 'ultrices libero non mattis pulvinar nulla pede ullamcorper augue a suscipit nulla elit ac nulla sed vel enim sit amet nunc viverra dapibus nulla suscipit ligula in lacus curabitur at ipsum ac tellus semper interdum mauris ullamcorper purus sit amet nulla quisque arcu libero rutrum ac lobortis vel dapibus', 1),
(406, 'morbi sem', '906.11', 8, NULL, 0),
(407, 'turpis sed ante', '614.40', 13, NULL, 1),
(408, 'pellentesque at nulla suspendisse', '320.90', 9, 'congue risus semper porta volutpat quam pede lobortis ligula sit amet eleifend pede libero quis orci nullam molestie nibh in lectus pellentesque at nulla suspendisse potenti cras in purus eu magna vulputate luctus cum sociis natoque penatibus et magnis dis parturient montes nascetur ridiculus', 1),
(409, 'porta volutpat', '594.52', 4, 'proin leo odio porttitor id consequat in consequat ut nulla sed accumsan felis ut at dolor quis odio consequat varius integer ac leo', 1),
(410, 'elementum nullam varius', '907.18', 4, 'platea dictumst etiam faucibus cursus urna ut tellus nulla ut erat id mauris vulputate elementum nullam varius nulla facilisi cras non velit nec nisi vulputate nonummy maecenas tincidunt lacus at velit vivamus vel nulla eget eros elementum', 0),
(411, 'libero ut massa', '898.87', 6, 'libero nam dui proin leo odio porttitor id consequat in consequat ut nulla sed accumsan felis ut at dolor quis odio consequat varius integer ac leo pellentesque ultrices mattis odio donec vitae nisi nam ultrices libero non', 1),
(412, 'faucibus accumsan odio', '532.69', 9, NULL, 1),
(413, 'odio', '607.47', 12, 'dolor quis odio consequat varius integer ac leo pellentesque ultrices mattis odio donec vitae nisi nam', 0),
(414, 'urna ut tellus', '735.63', 6, 'faucibus orci luctus et ultrices posuere cubilia curae donec pharetra magna vestibulum aliquet ultrices erat tortor sollicitudin mi sit amet lobortis sapien', 0),
(415, 'quis', '456.48', 10, NULL, 1),
(416, 'ipsum', '708.89', 7, 'in felis donec semper sapien a libero nam dui proin leo odio porttitor id consequat in consequat ut nulla sed accumsan felis ut at dolor quis odio', 1),
(417, 'justo', '451.68', 10, 'ultrices posuere cubilia curae donec pharetra magna vestibulum aliquet ultrices erat tortor sollicitudin mi sit amet lobortis sapien sapien non mi integer ac neque duis bibendum morbi non quam', 0),
(418, 'quis lectus suspendisse', '261.89', 12, 'pulvinar nulla pede ullamcorper augue a suscipit nulla elit ac nulla sed vel enim sit amet nunc viverra dapibus nulla suscipit ligula in lacus curabitur at ipsum ac tellus semper interdum mauris ullamcorper purus sit amet nulla quisque arcu libero rutrum ac lobortis vel dapibus at diam nam', 1),
(419, 'lacus curabitur at ipsum', '928.23', 11, 'accumsan tortor quis turpis sed ante vivamus tortor duis mattis egestas metus aenean fermentum donec ut mauris eget massa tempor convallis nulla neque libero convallis eget eleifend luctus ultricies eu nibh quisque id justo sit amet sapien dignissim vestibulum vestibulum ante ipsum primis', 1),
(420, 'vehicula condimentum curabitur in', '708.41', 3, 'hac habitasse platea dictumst morbi vestibulum velit id pretium iaculis diam erat fermentum justo nec condimentum neque sapien placerat ante nulla justo aliquam quis turpis eget elit sodales scelerisque mauris sit amet eros suspendisse accumsan tortor quis turpis sed', 0),
(421, 'ultrices', '598.72', 2, NULL, 1),
(422, 'venenatis', '594.42', 2, NULL, 0),
(423, 'lacinia eget tincidunt', '446.83', 8, NULL, 0),
(424, 'elit ac', '117.73', 12, NULL, 1),
(425, 'posuere nonummy integer', '658.06', 11, NULL, 0),
(426, 'scelerisque mauris', '553.07', 13, 'in faucibus orci luctus et ultrices posuere cubilia curae donec pharetra magna vestibulum aliquet ultrices erat tortor sollicitudin mi sit amet', 0),
(427, 'et eros vestibulum ac', '834.30', 7, 'eu sapien cursus vestibulum proin eu mi nulla ac enim in tempor turpis nec euismod', 0),
(428, 'aliquam lacus morbi quis', '368.11', 2, 'rutrum rutrum neque aenean auctor gravida sem praesent id massa id nisl venenatis lacinia aenean sit amet justo morbi ut odio cras mi pede malesuada in imperdiet et commodo vulputate justo in blandit ultrices enim lorem ipsum dolor sit amet consectetuer adipiscing elit proin interdum mauris non ligula', 0),
(429, 'vel ipsum praesent blandit', '488.30', 10, 'semper porta volutpat quam pede lobortis ligula sit amet eleifend pede libero quis orci nullam molestie nibh in lectus pellentesque at nulla suspendisse potenti cras in purus eu magna vulputate luctus cum sociis natoque penatibus et magnis dis parturient montes nascetur ridiculus mus vivamus', 0),
(430, 'augue vestibulum ante', '733.59', 3, 'in blandit ultrices enim lorem ipsum dolor sit amet consectetuer adipiscing elit proin interdum mauris non ligula pellentesque ultrices phasellus id sapien in sapien iaculis congue vivamus metus arcu adipiscing molestie hendrerit at vulputate vitae nisl aenean lectus pellentesque', 1),
(431, 'nullam sit amet turpis', '216.84', 7, 'id justo sit amet sapien dignissim vestibulum vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae nulla dapibus dolor vel est donec odio', 1),
(432, 'sem praesent', '359.23', 13, 'elementum pellentesque quisque porta volutpat erat quisque erat eros viverra eget congue eget semper rutrum nulla nunc purus', 1),
(433, 'semper porta', '581.77', 13, 'nulla sed accumsan felis ut at dolor quis odio consequat varius integer ac leo pellentesque ultrices mattis odio donec vitae nisi nam ultrices libero non mattis pulvinar nulla pede ullamcorper augue a suscipit nulla elit', 0),
(434, 'sed magna', '148.76', 5, 'proin eu mi nulla ac enim in tempor turpis nec euismod scelerisque quam turpis adipiscing lorem vitae mattis nibh ligula nec sem duis aliquam convallis nunc proin at turpis a pede posuere nonummy integer non', 1),
(435, 'pede lobortis', '953.77', 13, 'sagittis nam congue risus semper porta volutpat quam pede lobortis ligula sit amet eleifend pede libero quis orci', 1),
(436, 'molestie nibh in', '928.69', 9, NULL, 0),
(437, 'diam neque vestibulum eget', '845.32', 7, 'ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae nulla dapibus dolor vel est donec odio justo sollicitudin ut suscipit a feugiat et eros vestibulum ac est lacinia nisi', 1),
(438, 'luctus et', '112.73', 3, 'nulla tellus in sagittis dui vel nisl duis ac nibh fusce lacus purus', 0),
(439, 'pede justo lacinia', '676.16', 13, 'sit amet consectetuer adipiscing elit proin risus praesent lectus vestibulum quam sapien varius ut blandit non interdum in ante vestibulum ante ipsum primis in faucibus orci luctus', 0),
(440, 'metus aenean fermentum', '635.03', 4, 'rutrum nulla nunc purus phasellus in felis donec semper sapien a libero nam dui proin leo odio porttitor id consequat in consequat ut nulla sed accumsan felis ut at', 0),
(441, 'sed sagittis nam congue', '397.15', 13, NULL, 0),
(442, 'nunc commodo placerat', '116.27', 6, 'nisi at nibh in hac habitasse platea dictumst aliquam augue quam sollicitudin vitae consectetuer eget rutrum at lorem integer tincidunt ante vel ipsum praesent blandit lacinia erat vestibulum sed magna at nunc commodo placerat praesent blandit', 0),
(443, 'non velit donec diam', '275.38', 6, 'amet erat nulla tempus vivamus in felis eu sapien cursus vestibulum proin eu mi nulla ac enim in tempor turpis nec euismod scelerisque quam turpis adipiscing lorem vitae mattis nibh ligula nec sem duis aliquam convallis nunc proin at turpis a pede posuere', 0),
(444, 'donec quis', '484.94', 2, 'turpis a pede posuere nonummy integer non velit donec diam neque vestibulum eget vulputate ut ultrices vel augue vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae donec pharetra magna vestibulum aliquet ultrices erat tortor sollicitudin mi sit amet lobortis sapien sapien non mi integer', 0),
(445, 'odio in', '648.50', 5, 'tempus sit amet sem fusce consequat nulla nisl nunc nisl duis bibendum felis sed interdum venenatis turpis enim blandit mi in porttitor pede justo eu massa donec dapibus duis at velit eu est congue elementum in hac habitasse platea dictumst morbi vestibulum velit', 1),
(446, 'lacus morbi', '184.81', 2, 'nibh in lectus pellentesque at nulla suspendisse potenti cras in purus eu magna vulputate luctus cum sociis natoque penatibus et magnis dis parturient montes nascetur ridiculus mus vivamus vestibulum sagittis sapien cum sociis natoque penatibus et magnis dis parturient montes nascetur', 0),
(447, 'ultricies eu nibh quisque', '212.71', 4, 'sit amet diam in magna bibendum imperdiet nullam orci pede venenatis non sodales sed tincidunt eu felis fusce posuere felis sed lacus morbi sem mauris laoreet ut rhoncus aliquet pulvinar sed nisl', 0),
(448, 'luctus nec', '516.79', 9, NULL, 0),
(449, 'ut volutpat sapien arcu', '277.15', 2, 'scelerisque quam turpis adipiscing lorem vitae mattis nibh ligula nec sem duis aliquam convallis nunc proin at turpis a pede posuere nonummy integer non velit donec diam neque vestibulum eget vulputate ut ultrices vel augue vestibulum ante ipsum', 0),
(450, 'nibh', '367.73', 6, 'accumsan tortor quis turpis sed ante vivamus tortor duis mattis egestas metus aenean fermentum donec ut mauris eget massa tempor convallis nulla neque libero convallis eget eleifend luctus ultricies eu nibh quisque id justo sit amet sapien dignissim vestibulum vestibulum ante ipsum primis in faucibus orci luctus et ultrices', 1),
(451, 'lobortis sapien', '178.02', 7, 'donec posuere metus vitae ipsum aliquam non mauris morbi non lectus aliquam sit amet diam in magna bibendum imperdiet nullam orci pede venenatis non sodales sed tincidunt eu felis fusce posuere felis sed lacus morbi sem mauris laoreet ut rhoncus aliquet pulvinar sed nisl', 0),
(452, 'vulputate justo in', '491.29', 9, 'quam fringilla rhoncus mauris enim leo rhoncus sed vestibulum sit amet cursus id turpis integer aliquet massa id lobortis convallis tortor risus', 1),
(453, 'quam pharetra', '864.61', 6, 'at nulla suspendisse potenti cras in purus eu magna vulputate luctus cum sociis natoque penatibus et magnis dis parturient montes nascetur ridiculus mus vivamus vestibulum sagittis sapien cum sociis natoque penatibus', 1),
(454, 'morbi sem mauris laoreet', '190.44', 7, NULL, 1),
(455, 'nibh in hac habitasse', '902.65', 7, NULL, 0),
(456, 'odio porttitor id consequat', '658.04', 10, NULL, 0),
(457, 'tristique tortor eu pede', '100.74', 9, 'magna vulputate luctus cum sociis natoque penatibus et magnis dis parturient montes nascetur', 0),
(458, 'sem fusce', '391.42', 6, 'ac leo pellentesque ultrices mattis odio donec vitae nisi nam ultrices libero non mattis pulvinar nulla pede ullamcorper augue a suscipit nulla', 0),
(459, 'fusce consequat nulla nisl', '806.04', 2, 'sed magna at nunc commodo placerat praesent blandit nam nulla integer pede', 0),
(460, 'sapien cursus vestibulum', '159.22', 10, 'sollicitudin ut suscipit a feugiat et eros vestibulum ac est lacinia nisi venenatis tristique fusce congue diam id ornare imperdiet sapien urna pretium nisl ut volutpat sapien arcu sed augue aliquam', 1),
(461, 'aliquet maecenas leo odio', '890.87', 9, 'sed accumsan felis ut at dolor quis odio consequat varius integer ac leo pellentesque ultrices mattis odio donec vitae nisi', 0),
(462, 'ligula vehicula consequat morbi', '356.83', 10, 'congue elementum in hac habitasse platea dictumst morbi vestibulum velit id pretium iaculis diam erat fermentum justo nec condimentum neque sapien placerat ante nulla justo aliquam quis turpis eget elit sodales', 1),
(463, 'donec ut', '975.85', 4, 'tempus sit amet sem fusce consequat nulla nisl nunc nisl duis bibendum felis sed interdum venenatis turpis enim blandit mi in porttitor pede justo eu massa donec dapibus', 0),
(464, 'cras', '809.14', 4, 'ut tellus nulla ut erat id mauris vulputate elementum nullam varius nulla facilisi cras non velit nec nisi vulputate nonummy maecenas tincidunt lacus at velit vivamus vel nulla eget eros elementum pellentesque quisque porta volutpat erat quisque erat eros viverra eget congue eget semper', 1),
(465, 'fringilla rhoncus mauris', '661.86', 7, 'vitae mattis nibh ligula nec sem duis aliquam convallis nunc proin at turpis a pede posuere nonummy integer non velit donec diam neque vestibulum eget', 0),
(466, 'ultrices mattis', '342.74', 5, NULL, 0),
(467, 'pulvinar', '266.87', 6, NULL, 0),
(468, 'libero ut massa volutpat', '709.43', 3, 'sed interdum venenatis turpis enim blandit mi in porttitor pede justo eu massa donec dapibus duis', 1),
(469, 'dolor sit amet consectetuer', '144.98', 2, 'magna vulputate luctus cum sociis natoque penatibus et magnis dis parturient montes nascetur ridiculus mus vivamus vestibulum sagittis sapien cum sociis natoque penatibus et magnis dis parturient montes nascetur ridiculus mus etiam vel augue vestibulum rutrum rutrum neque aenean auctor gravida sem praesent id massa id nisl', 1),
(470, 'pede ac', '189.04', 13, 'eu sapien cursus vestibulum proin eu mi nulla ac enim in tempor turpis nec euismod scelerisque quam turpis adipiscing lorem vitae mattis nibh ligula nec sem duis aliquam convallis nunc proin at turpis a pede posuere nonummy integer non velit donec', 0),
(471, 'montes nascetur ridiculus', '455.81', 3, 'eget eros elementum pellentesque quisque porta volutpat erat quisque erat eros viverra eget congue eget semper rutrum nulla nunc purus phasellus in felis donec semper sapien a libero nam dui proin leo odio porttitor id consequat in consequat ut nulla sed accumsan felis ut at dolor quis', 0),
(472, 'ut odio cras', '454.49', 5, 'malesuada in imperdiet et commodo vulputate justo in blandit ultrices enim lorem ipsum dolor sit amet consectetuer adipiscing elit proin interdum mauris non ligula pellentesque ultrices phasellus id sapien in sapien iaculis congue vivamus metus arcu adipiscing molestie hendrerit at vulputate vitae nisl aenean lectus pellentesque eget nunc', 0),
(473, 'rhoncus', '321.39', 13, NULL, 1),
(474, 'lectus', '537.49', 8, 'quam turpis adipiscing lorem vitae mattis nibh ligula nec sem duis aliquam convallis', 1),
(475, 'erat', '661.74', 12, 'varius nulla facilisi cras non velit nec nisi vulputate nonummy maecenas tincidunt lacus at velit vivamus vel nulla eget eros elementum pellentesque quisque porta volutpat erat quisque erat eros viverra eget congue eget semper rutrum nulla', 0),
(476, 'et ultrices posuere', '152.49', 6, NULL, 0),
(477, 'pellentesque volutpat', '307.09', 7, NULL, 0),
(478, 'vulputate justo', '632.72', 13, NULL, 1),
(479, 'orci', '544.90', 13, 'scelerisque quam turpis adipiscing lorem vitae mattis nibh ligula nec sem duis aliquam convallis nunc proin at turpis a pede posuere nonummy integer non velit donec diam neque vestibulum eget vulputate ut ultrices vel augue vestibulum ante ipsum primis in faucibus', 1),
(480, 'blandit ultrices', '361.40', 3, 'amet diam in magna bibendum imperdiet nullam orci pede venenatis non sodales sed tincidunt eu felis fusce posuere felis sed lacus morbi sem mauris laoreet ut rhoncus aliquet pulvinar sed nisl', 0),
(481, 'maecenas tristique', '647.87', 5, NULL, 1),
(482, 'justo eu', '166.38', 4, 'purus eu magna vulputate luctus cum sociis natoque penatibus et magnis dis parturient montes nascetur ridiculus mus vivamus vestibulum sagittis sapien cum sociis', 0),
(483, 'neque duis', '211.81', 3, NULL, 0),
(484, 'ut', '854.93', 10, 'faucibus orci luctus et ultrices posuere cubilia curae mauris viverra diam vitae quam suspendisse potenti nullam porttitor lacus at turpis donec posuere metus vitae ipsum aliquam non mauris morbi non lectus aliquam sit amet diam in magna', 1),
(485, 'volutpat', '278.41', 8, 'justo morbi ut odio cras mi pede malesuada in imperdiet et commodo vulputate justo in blandit ultrices enim lorem ipsum dolor sit amet consectetuer adipiscing elit proin interdum mauris non ligula pellentesque ultrices phasellus id sapien in sapien iaculis congue vivamus', 0),
(486, 'condimentum id', '674.43', 12, 'elementum in hac habitasse platea dictumst morbi vestibulum velit id pretium iaculis diam erat fermentum justo nec condimentum neque sapien placerat ante nulla justo aliquam quis', 0),
(487, 'vivamus in felis', '917.14', 13, 'libero ut massa volutpat convallis morbi odio odio elementum eu interdum eu tincidunt', 1),
(488, 'ultrices libero non mattis', '219.85', 7, NULL, 1),
(489, 'nulla', '296.93', 11, 'massa tempor convallis nulla neque libero convallis eget eleifend luctus ultricies eu nibh quisque id justo sit amet sapien dignissim vestibulum vestibulum ante ipsum primis', 1),
(490, 'bibendum', '943.31', 9, 'eget orci vehicula condimentum curabitur in libero ut massa volutpat convallis morbi odio odio elementum eu interdum eu', 1),
(491, 'aliquet', '815.21', 6, 'non velit donec diam neque vestibulum eget vulputate ut ultrices vel augue vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae donec pharetra magna vestibulum aliquet ultrices erat tortor sollicitudin mi sit amet', 1),
(492, 'nisl duis ac', '743.75', 3, 'aliquam augue quam sollicitudin vitae consectetuer eget rutrum at lorem integer tincidunt ante vel ipsum praesent blandit lacinia erat vestibulum sed magna at nunc commodo placerat praesent blandit nam', 1),
(493, 'aenean sit amet', '482.87', 2, 'mattis pulvinar nulla pede ullamcorper augue a suscipit nulla elit ac nulla sed vel enim sit amet nunc viverra dapibus nulla suscipit ligula in lacus curabitur at ipsum ac tellus semper interdum mauris ullamcorper purus sit amet nulla quisque arcu libero rutrum ac', 1),
(494, 'dolor', '416.42', 10, 'eget congue eget semper rutrum nulla nunc purus phasellus in felis donec semper sapien a libero nam dui proin leo odio porttitor id consequat in consequat ut nulla sed accumsan felis ut at dolor quis odio consequat varius integer ac leo pellentesque ultrices mattis odio donec', 1),
(495, 'elementum', '641.14', 8, 'turpis sed ante vivamus tortor duis mattis egestas metus aenean fermentum donec ut mauris eget massa tempor convallis nulla neque libero convallis eget eleifend luctus ultricies eu nibh quisque id justo sit amet sapien dignissim vestibulum vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae nulla', 1),
(496, 'velit eu est congue', '306.35', 2, 'luctus et ultrices posuere cubilia curae nulla dapibus dolor vel est donec odio justo sollicitudin ut suscipit a feugiat et eros vestibulum ac est lacinia nisi venenatis tristique fusce congue diam', 0),
(497, 'justo morbi', '797.72', 8, 'interdum eu tincidunt in leo maecenas pulvinar lobortis est phasellus sit amet erat nulla tempus vivamus in felis eu sapien cursus vestibulum proin eu mi nulla ac', 1),
(498, 'mi pede malesuada in', '881.91', 6, NULL, 1),
(499, 'donec odio', '846.60', 7, NULL, 1),
(500, 'sit amet consectetuer adipiscing', '205.90', 9, 'in faucibus orci luctus et ultrices posuere cubilia curae donec pharetra magna vestibulum aliquet ultrices erat tortor sollicitudin mi sit amet lobortis sapien sapien non mi integer ac neque duis bibendum morbi non quam', 0),
(501, 'maecenas leo odio', '340.82', 10, 'nisi at nibh in hac habitasse platea dictumst aliquam augue quam sollicitudin vitae consectetuer eget rutrum at lorem integer tincidunt ante vel ipsum', 0),
(502, 'a', '894.47', 3, 'nec dui luctus rutrum nulla tellus in sagittis dui vel nisl duis ac', 1),
(507, 'Основы взаимодействия', '102.99', 9, 'Равным образом постоянный количественный рост и сфера нашей активности укрепляет нас, в нашем стремлении улучшения новейших вариантов поиска решений.\r\nТоварищи, сложившаяся ситуация ни коим образом не обеспечивает широкому кругу (специалистов) участие в формировании позиций, занимаемых участниками в отношении поставленных задач.\r\nТеперь становится очевидно, что реализация намеченных плановых заданий обеспечивает широкому кругу (специалистов) участие в формировании и анализу необходимых данных для разрешения ситуации в целом.\r\nТем не менее стоит отметить так же, что дальнейшее развитие различных форм деятельности играет важную роль в формировании новейших вариантов поиска решений.', 1),
(509, 'sdf', '50.00', 1, 'xc', 0),
(511, 'test new style', '76.00', 1, 'fghjkgfdfghj dsdfghjkl tdsetyuilknbvcddfghj fddfgjkjbvc', 0),
(512, 'zdfghgfdfgh', '56.00', 1, 'ghjklkgfhj', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `help`
--

CREATE TABLE `help` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `help`
--

INSERT INTO `help` (`id`, `name`, `title`, `content`) VALUES
(1, 'Config/db.php', 'Настройки базы данных', '<h4>Обзор:</h4>\r\n<pre>\r\n    return array(\r\n        \'<b>host</b>\'=>\'localhost\',\r\n        \'<b>base</b>\'=>\'my_base\',\r\n        \'<b>user</b>\'=>\'root\',\r\n        \'<b>pass</b>\'=>\'1234\',\r\n    );\r\n</pre>\r\n\r\n<p>\r\n    <b>host</b> - Хост. <br>\r\n    <b>base</b> - Имя базы данных. <br>\r\n    <b>user</b> - Имя пользователя. <br>\r\n    <b>pass</b> - Пароль пользователя. <br>\r\n</p>'),
(2, 'Config/routes.php', 'Файл роутеров', '<h4>Обзор:</h4>\r\n<pre>\r\nreturn array(\r\n    \"<b>Name</b>\"=>[\"<b>Controller</b>\",\"<b>Action</b>\",\"<b>Url</b>\",[\"<b>Params</b>\"],\"<b>Loyout</b>\",[\"<b>?</b>\"]],\r\n);\r\n</pre>\r\n<p>\r\n    <b>Name</b> - Имя роута. <br>\r\n    <b>Controller</b> - Имя контроллера. <br>\r\n    <b>Action</b> - Имя экшена. <br>\r\n    <b>Url</b> - Url адрес. <br>\r\n    <b>Params</b> - Массив с параметрами. <br>\r\n    <b>Loyout</b> - Отдельный loyout. <br>\r\n</p>\r\n\r\n<br>\r\n<h4>Пример:</h4>\r\n<pre>\r\nreturn array(\r\n    \"default\"=>[\"default\",\"index\",\"/\"],\r\n    \"index\"=>[\"default\",\"index\",\"/index.html\"],\r\n);\r\n</pre>\r\n\r\n<br>\r\n<h4>Пример <small>с параметрами</small>:</h4>\r\n<pre>\r\nreturn array(\r\n    \"books\"=>[\"book\",\"index\",\"/book_page-{<b>page</b>}.html\",[\"<b>page</b>\"=>\"[0-9]+\"]],\r\n    \"book_show\"=>[\"book\",\"show\",\"/book_show-{<b>id</b>}.html\",[\"<b>id</b>\"=>\"[0-9]+\"]],\r\n);\r\n</pre>\r\n\r\n<br>\r\n<h4>Пример <small>с отдельным loyout</small>:</h4>\r\n<pre>\r\nreturn array(\r\n    \"videos\"=>[\"video\",\"index\",\"<b>/video</b>\",null,\"<b>video_loyout.php</b>\"],\r\n    \"video_show\"=>[\"video\",\"show\",\"<b>/video</b>/show/{id}\",[\"id\"=>\"[0-9]+\"]],\r\n);\r\n</pre>\r\n<p>Если поставить Loyout  на начало пути url <b>/video</b> то <b>/video</b>/show/10  тоже будет с <b>video_loyout.php</b></p>'),
(3, 'Config/config.php', 'Файл конфигураций', '<h4>Обзор:</h4>\r\n<pre>\r\nreturn array(\r\n    \"<b>Name</b>\"=>\"<b>Value</b>\",\r\n);\r\n</pre>\r\n<p>\r\n    <b>Name</b> - Название конфигурации. <br>\r\n    <b>Value</b> - Значение конфигурации. <br>\r\n</p>\r\n\r\n<br>\r\n<h4>Пример:</h4>\r\n<pre>\r\nreturn array(\r\n    \"errorController\"=>\"default\",\r\n    \"errorAction\"=>\"error\",\r\n    \"layout\" => \'layout.phtml\',\r\n);\r\n</pre>');

-- --------------------------------------------------------

--
-- Структура таблицы `style`
--

CREATE TABLE `style` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `title` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `style`
--

INSERT INTO `style` (`id`, `title`) VALUES
(1, 'none'),
(2, 'Computers'),
(3, 'Test'),
(4, 'Outdoors'),
(5, 'Shoes'),
(6, 'Electronics'),
(7, 'Beauty'),
(8, 'Music'),
(9, 'Kids'),
(10, 'Games'),
(11, 'Home'),
(12, 'Books'),
(13, 'Baby');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `active`, `admin`) VALUES
(1, 'admin', 'admin@com', 'e17cf30a4431cd5268deed8fcdf7b21a', '0', 1),
(2, 'user3', 'user3@com', '7f43a0c1bbb18bb59a2ace77810d5f7e', '0', 0),
(3, 'user4', 'user4@com', 'ce01ca3db4632b3cd2b80fa4e7c14a03', '0dc0dcc4f5825e791f252e4662ed7aa9', 0),
(4, 'user5', 'user5@com', 'd4fa82538114ec98d2fa03628dd18148', '8e997f2f6c3105113508637f06350407', 0),
(5, 'admin', 'admin@com.com', '1d43a95f76d1da7b3c39597ecf00121e', '0', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `style_id` (`style_id`);

--
-- Индексы таблицы `help`
--
ALTER TABLE `help`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `style`
--
ALTER TABLE `style`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `book`
--
ALTER TABLE `book`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=513;
--
-- AUTO_INCREMENT для таблицы `help`
--
ALTER TABLE `help`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `style`
--
ALTER TABLE `style`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
