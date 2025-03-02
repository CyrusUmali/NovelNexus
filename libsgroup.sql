-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2025 at 12:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `libsgroup`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `bookcategories`
--

CREATE TABLE `bookcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookcategories`
--

INSERT INTO `bookcategories` (`id`, `category_id`, `book_id`) VALUES
(5, 2, 5),
(6, 4, 5),
(7, 1, 6),
(8, 2, 6),
(9, 4, 6),
(11, 6, 7),
(12, 6, 8),
(13, 3, 9),
(14, 5, 9),
(15, 3, 10),
(16, 1, 2),
(17, 2, 2),
(18, 3, 2),
(19, 4, 2),
(20, 5, 2),
(21, 6, 2),
(22, 4, 11),
(23, 1, 11),
(24, 5, 12),
(27, 5, 1),
(28, 4, 4),
(30, 2, 13),
(32, 2, 3),
(33, 1, 14),
(34, 5, 14);

-- --------------------------------------------------------

--
-- Table structure for table `bookreviews`
--

CREATE TABLE `bookreviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review_content` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `book_isbn` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookreviews`
--

INSERT INTO `bookreviews` (`id`, `user_id`, `book_id`, `rating`, `review_content`, `created_at`, `book_isbn`) VALUES
(18, 24, 1, 3, '<p>This is really nice</p>', '2024-11-27 16:18:21', 'SLVS123'),
(19, 11, 12, 5, NULL, '2024-11-27 16:54:11', 'SLVS123'),
(20, 27, 3, 4, NULL, '2024-11-29 15:55:13', 'AG123'),
(21, 27, 6, 5, NULL, '2024-11-29 15:59:17', 'TBD123'),
(22, 27, 2, 4, '<p>This book is really nice!</p>', '2024-11-29 16:05:27', 'AH123'),
(23, 24, 3, 4, '<p>Very Nice</p>', '2024-11-29 16:44:28', 'AG123'),
(24, 28, 4, 5, NULL, '2024-12-05 18:39:24', 'KS123'),
(25, 24, 8, 5, NULL, '2024-12-18 13:03:51', 'HTW123'),
(26, 24, 6, 4, '<p>Really love this book </p><p><br></p>', '2024-12-18 13:28:35', 'TBD123');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(255) NOT NULL,
  `book_cover` varchar(999) NOT NULL,
  `about` text NOT NULL,
  `Availability` varchar(255) NOT NULL DEFAULT 'Available',
  `accesslvl` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `isbn`, `book_cover`, `about`, `Availability`, `accesslvl`) VALUES
(1, 'Salvos', 'Melasd', 'SLVS123', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1731583572/testUpload/utm9fwv6y8oienucwz7r.jpg', '<p><em>Follow the evolution of a Demon girl as she learns to survive in a fantasy world!</em></p><p>The life of an Infant Demon is a bloody grind to the top. For Salvos, a curious newborn Demon with a penchant for making friends, surviving the swarms of wild Demons in the Netherworld was always going to be a difficult task. She will adapt, gain experience, and evolve to survive this hellish landscape with the help of her sole companion.</p><p>But when her companion\'s life is threatened by a mysterious Demon King, she\'ll have to do what it takes to save him. Even if it means separating from him and being tossed into an unfamiliar world with Humans, monsters, and a bright blue sky where she is scorned for being born a Demon.</p><p>However, the law of evolution is survival of the fittest, and no matter where she is, Salvos will survive.</p><p>Do note that it\'s a slow-paced but action-packed litrpg!</p>', 'Loaned', 1),
(2, 'Azarinth Healer', 'Rhaegar', 'AH123', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1731584282/testUpload/nxgwe6fv2wm2bma5cggt.jpg', '<p>A new world with nearly unlimited possibilities. A status, classes, magic and monsters. Sounds good? Well, for Ilea it didn\'t come quite as expected as for some other protagonists, nor was there a king or god to welcome her.</p><p>The grand quest? Well, she might figure that out someday but for now, a new world with new food is prize enough. Her fists at the ready, she\'s prepared to punch and get punched, however long it takes and however many limbs she might have to regrow.</p><p>A story I\'ve started writing now quite a while ago. Transported to another world, somewhat standard fantasy setting with my beginner attempts to make it dark but funny. There are Litrpg elements here but I do hope it\'s not too heavy and annoying. The fights should be interesting and aren\'t just numbers vs numbers. Contrary to the title the protagonist will be quite an offensive fighter.</p><p>Ilea Spears is your average sarcastic kick-boxing fast food worker and soon to be student. She will be transported to another world rather conventionally and will be confronted with survival in the wild.</p>', 'Available', 0),
(3, 'Adulthood Is A Gift', 'Sarah Andersen', 'AG123', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1731584523/testUpload/mbw9zruf5v7lpwtjvwuu.jpg', '<p><span style=\"color: rgb(30, 25, 21);\">With 100 comics, 15 essays, and dozens of photos and sketches, the fifth&nbsp;</span><em style=\"color: rgb(30, 25, 21);\">Sarah\'s Scribbles</em><span style=\"color: rgb(30, 25, 21);\">&nbsp;book offers a rare look behind the creative process of one of the most original and beloved comic artists of a generation. A comedic companion to her first book, the bestselling&nbsp;</span><em style=\"color: rgb(30, 25, 21);\">Adulthood is a Myth</em><span style=\"color: rgb(30, 25, 21);\">, the artwork and writing in&nbsp;</span><em style=\"color: rgb(30, 25, 21);\">Adulthood is a Gift!&nbsp;is a celebration of the many experiences and life lessons the author has picked up in her decade of making viral, relatable, and award-winning comics and books. This book also includes a sticker sheet.</em></p>', 'Loaned', 0),
(4, 'Konosuba', 'Natsume Atsuki', 'KS123', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1731584630/testUpload/cnkh7soqbkng3jhleyuu.jpg', '<p>Kazuma Satou lives a laughable and pathetic life, being a shut-in NEET with no distinguishable qualities other than an addiction to video games. On his way home, Kazuma dies trying to save a girl from an oncoming truck—or so he believes. In reality, the \"truck\" was a slow-moving tractor, and he merely died from shock.</p><p><br></p><p><br></p><p><br></p><p>Waking up in limbo between death and heaven, Kazuma finds himself facing the arrogant goddess Aqua. Here, he must choose between two options: go on to heaven or be sent to a fantasy world that needs his help to defeat the Demon King. Initially unimpressed by the challenging prospect of fighting a Demon King, Kazuma changes his mind after Aqua tells him he can bring any one item he wants. What better choice does Kazuma have than the goddess standing before him?</p><p><br></p><p><br></p><p><br></p><p>Unfortunately, after the two arrive in their new world, two things become clear: Aqua is useless beyond belief, and life in this fantasy realm will be anything but smooth sailing. From paying for food and accommodations to trying to learn new skills, the duo\'s problems are just starting to take shape—and the arrival of eccentric allies may only make things worse.</p>', 'Available', 1),
(5, 'Diary ng Panget', 'HaveYouSeenThisGirL', 'DP123', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1732001740/testUpload/x2wiyohpewdwacgiweuk.jpg', '<p><span style=\"color: rgb(30, 25, 21);\">Mahirap at panget si Girl tapos magnet siya ng mga poging mayayaman na boys? YES! Cliché? YES! So what makes this book special? This story has made a lot of people online laugh, as in hagalpak talaga with matching headbang pa! This is Eya\'s diary, a girl who believes she\'s ugly and will meet Cross Sandford, the most annoying nilalang ever. Samahan natin si Eya sa nakakaloka niyang adventure sa Willford Academy! A Cinderella story with a twist katatawanan! A story na pwedeng-pwede sa mga kabataan at pati na rin sa lagpas kabataan, para sa kababaihan, kalalakihan, binabae, o pusong lalaki. A very funny and kakilig story.</span></p>', 'Available', 0),
(6, 'Talk Back and You\'re Dead!', 'Alesana Marie', 'TBD123', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1732001857/testUpload/hmgi2dawggw6ycypundv.jpg', '<p><span style=\"color: rgb(30, 25, 21);\">May nakilala na ba kayong (napakagwapo) nakakatakot na nilalalang na may kakayahang maglabas ng laser beam sa mga mata kapag nagagalit?</span></p><p><span style=\"color: rgb(30, 25, 21);\">Eh ang maging boyfriend sya dahil sa (kabaliwan) kakaibang trip ng mga kaibigan mo?</span></p><p><br></p><p><span style=\"color: rgb(30, 25, 21);\">At ang mapaligiran ng mga (hotties) hot-headed na katulad nya? Ang tahimik kong buhay ay biglang naging parang action movie, may mga habulan at fighting scenes.</span></p><p><br></p><p><span style=\"color: rgb(30, 25, 21);\">Ako si Sam, isang perfect student, maganda matalino at mabait). Boyfriend ko si TOP, isang delinquent, gang leader at cussing machine.</span></p>', 'Available', 0),
(7, 'The PLAN: Manage Your Time Like a Lazy Genius', 'Kendra Adachi', 'TPM123', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1732001981/testUpload/hlyovd2ggzgj3c1de1tf.png', '<p><span style=\"color: rgb(30, 25, 21);\">The New York Times bestselling author of The Lazy Genius Way brings her beloved Kind Big Sister Energy to a time management book for productivity-weary people who want to live an easier life, not do more homework.</span></p><p><br></p><p><span style=\"color: rgb(30, 25, 21);\">Why do so-called life hacks leave us&nbsp;drowning in tasks, schedules, and unfulfilled expectations?&nbsp;In her straightforward, humorous style, author Kendra Adachi reveals why the problem is not you.</span></p><p><br></p><p><span style=\"color: rgb(30, 25, 21);\">Most time management systems prioritize&nbsp;optimization and greatness in service to an imagined future, but what if that\'s not your goal? What if you long for a book that helps you live wholeheartedly today?&nbsp;The PLAN&nbsp;is the answer.</span></p><p><br></p><p><span style=\"color: rgb(30, 25, 21);\">Using the&nbsp;memorable acronym \"PLAN,\" you will learn to&nbsp;prepare,&nbsp;live,&nbsp;adjust, and&nbsp;notice like a Lazy Genius, all through the lens of what matters to you in your current season:</span></p><p><span style=\"color: rgb(30, 25, 21);\">• Discover two beliefs that will change time management forever</span></p><p><span style=\"color: rgb(30, 25, 21);\">• Integrate your hormones, personality, and life stage into your planning process</span></p><p><span style=\"color: rgb(30, 25, 21);\">• Learn the Lighten the Load to-do list framework to help you get your stuff done</span></p><p><span style=\"color: rgb(30, 25, 21);\">• Use The PLAN Pyramid to help you visualize a balanced life</span></p><p><span style=\"color: rgb(30, 25, 21);\">• Experience freedom from the crushing pressure of greatness, potential, and hustle</span></p><p><br></p><p><span style=\"color: rgb(30, 25, 21);\">Refreshingly compassionate and immediately practical,&nbsp;The PLAN&nbsp;is the book you\'ve been waiting for.</span></p>', 'Available', 0),
(8, 'How to Walk into a Room: The Art of Knowing When to Stay and When to Walk Away', 'Emily P. Freeman', 'HTW123', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1732002081/testUpload/cwlkv5yoqm0nztw4293j.jpg', '<p><span style=\"color: rgb(30, 25, 21);\">If life were a house, then every room holds a story. What do we do when a room we’re in is no longer a room where we belong?</span></p>', 'Available', 0),
(9, 'My Darling Dreadful Thing', 'Johanna van Veen', 'MDD123', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1732002178/testUpload/sfozkub0upux5aym88q2.jpg', '<p><span style=\"color: rgb(30, 25, 21);\">Roos Beckman has a spirit companion only she can see. Ruth—strange, corpse-like, and dead for centuries—is the only good thing in Roos’ life, which is filled with sordid backroom séances organized by her mother. That is, until wealthy young widow Agnes Knoop attends one of these séances and asks Roos to come live with her at the crumbling estate she inherited upon the death of her husband. The manor is unsettling, but the attraction between Roos and Agnes is palpable. So how does someone end up dead?</span></p><p><br></p><p><span style=\"color: rgb(30, 25, 21);\">Roos is caught red-handed, but she claims a spirit is the culprit. Doctor Montague, a psychologist tasked with finding out whether Roos can be considered mentally fit to stand trial, suspects she’s created an elaborate fantasy to protect her from what really happened. But Roos knows spirits are real; she\'s loved one of them. She\'ll have to prove her innocence and her sanity, or lose everything.</span></p>', 'Available', 0),
(10, 'Diavola', 'Jennifer Marie Thorne', 'DV123', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1732002222/testUpload/fbxczikz8d032reqjk0f.jpg', '<p><span style=\"color: rgb(30, 25, 21);\">Jennifer Thorne skewers all-too-familiar family dynamics in this sly, wickedly funny vacation-Gothic. Beautifully unhinged and deeply satisfying,&nbsp;</span><em style=\"color: rgb(30, 25, 21);\">Diavola</em><span style=\"color: rgb(30, 25, 21);\">&nbsp;is a sharp twist on the classic haunted house story, exploring loneliness, belonging, and the seemingly inescapable bonds of family mythology.</span></p>', 'Available', 0),
(11, 'Boneshaker', 'Cherie Priest', 'BS123', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1732238379/testUpload/niybkj9tgjcmacxwczi4.jpg', '<p><span style=\"color: rgb(30, 25, 21);\">In the early days of the Civil War, rumors of gold in the frozen Klondike brought hordes of newcomers to the Pacific Northwest. Anxious to compete, Russian prospectors commissioned inventor Leviticus Blue to create a great machine that could mine through Alaska’s ice. Thus was Dr. Blue’s Incredible Bone-Shaking Drill Engine born.</span></p>', 'Available', 0),
(12, 'Salvos', 'Melasd', 'SLVS123', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1732535061/testUpload/xe2z1pd6auufoqtsr0cv.jpg', '<p><em>Follow the evolution of a Demon girl as she learns to survive in a fantasy world!</em></p><p>The life of an Infant Demon is a bloody grind to the top. For Salvos, a curious newborn Demon with a penchant for making friends, surviving the swarms of wild Demons in the Netherworld was always going to be a difficult task. She will adapt, gain experience, and evolve to survive this hellish landscape with the help of her sole companion.</p><p>But when her companion\'s life is threatened by a mysterious Demon King, she\'ll have to do what it takes to save him. Even if it means separating from him and being tossed into an unfamiliar world with Humans, monsters, and a bright blue sky where she is scorned for being born a Demon.</p><p>However, the law of evolution is survival of the fittest, and no matter where she is, Salvos will survive.</p><p>Do note that it\'s a slow-paced but action-packed litrpg!</p>', 'Available', 0),
(14, 'Solo Levling', 'Chugoong', '67adw', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1735986586/testUpload/kfnlwdmocn0m24oe9vm8.jpg', '<p>10 years ago, after “the Gate” that connected the real world with the monster world opened, some of the ordinary, everyday people received the power to hunt monsters within the Gate. They are known as “Hunters”.</p><p>However, not all Hunters are powerful. My name is Sung Jin-Woo, an E-rank Hunter. I’m someone who has to risk his life in the lowliest of catacombs, the “World’s Weakest”.</p><p>Having no skills whatsoever to display, I barely earned the required money by fighting in low-leveled catacombs… at least until I found a hidden catacomb with the hardest difficulty within the D-rank catacombs!</p><p>In the end, as I was accepting death, I suddenly received a strange power, a quest log that only I could see, a secret to leveling up that only I know about! If I trained in accordance with my quests and hunted monsters, my level would rise.</p><p>Changing from the weakest Hunter to the strongest S-rank Hunter!</p>', 'Available', 0);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `photo` varchar(999) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `photo`) VALUES
(1, 'Action', 'https://membcdn.memorablequotations.com/1665726625726.jpg'),
(2, 'Romance', 'https://miblart.com/wp-content/uploads/2021/02/image6.png'),
(3, 'Horror', 'https://miblart.com/wp-content/uploads/2020/10/2LTd4fOY.jpeg'),
(4, 'Comedy', 'https://pixel.nymag.com/imgs/daily/vulture/2018/05/splitsider/comedy-books/dangerously-funny-david-bianculli.w536.h804.2x.jpg'),
(5, 'Fantasy', 'https://i.pinimg.com/originals/1c/c1/cc/1cc1cc765525b2e84f61547f88be6554.jpg'),
(6, 'Non-Fiction', 'https://i5.walmartimages.com/asr/813929e4-b49f-4d7b-b4be-f2e23036901a_1.1da5f8bd4485fc4f3da948c4819a789b.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `loan_from` date NOT NULL,
  `loan_to` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Loaned',
  `fine` int(11) DEFAULT 0,
  `paid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `book_id`, `user_id`, `loan_from`, `loan_to`, `status`, `fine`, `paid`) VALUES
(23, 5, 11, '2024-11-20', '2024-11-21', 'Returned', 300, 0),
(24, 1, 11, '2024-11-27', '2024-11-30', 'Overdue', 0, 0),
(25, 2, 24, '2024-11-24', '2024-12-12', 'Returned', 375, 1),
(26, 3, 27, '2024-11-29', '2024-12-05', 'Returned', 0, 0),
(27, 6, 27, '2024-11-29', '2024-12-01', 'Returned', 0, 0),
(28, 2, 27, '2024-10-29', '2024-10-30', 'Returned', 0, 0),
(29, 9, 24, '2024-11-29', '2024-12-03', 'Returned', 50, 1),
(30, 2, 24, '2024-11-29', '2025-01-04', 'Returned', 675, 1),
(31, 3, 24, '2024-11-29', '2024-12-08', 'Overdue', 675, 0),
(32, 6, 12, '2024-12-01', '2024-12-01', 'Returned', 0, 0),
(33, 4, 12, '2024-12-01', '2024-12-03', 'Returned', 25, 0),
(34, 6, 28, '2024-12-05', '2024-12-05', 'Returned', 0, 1),
(35, 4, 28, '2024-12-03', '2024-12-05', 'Returned', 25, 1),
(36, 4, 28, '2024-12-03', '2024-12-05', 'Returned', 25, 1),
(37, 4, 28, '2024-12-05', '2024-12-05', 'Returned', 0, 0),
(38, 4, 28, '2024-12-05', '2024-12-05', 'Returned', 0, 0),
(39, 4, 24, '2024-12-08', '2025-01-04', 'Returned', 575, 1),
(40, 2, 24, '2024-12-12', '2024-12-12', 'Returned', 0, 1),
(41, 9, 24, '2025-01-04', '2025-01-04', 'Returned', 0, 1),
(42, 10, 24, '2025-01-04', '2025-01-04', 'Returned', 0, 1),
(43, 12, 24, '2025-01-04', '2025-01-04', 'Returned', 0, 0),
(44, 5, 24, '2025-01-04', '2025-01-04', 'Returned', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `loan_id` int(11) DEFAULT NULL,
  `days_left` int(11) DEFAULT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(55) NOT NULL DEFAULT 'unread',
  `message` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `loan_id`, `days_left`, `created_at`, `status`, `message`, `user_id`) VALUES
(48, 24, 2, '2024-11-28', 'read', NULL, 11),
(49, 24, 2, '2024-11-28', 'read', NULL, 11),
(56, 25, NULL, '2024-11-28', 'read', 'A damage in the Book you borrowd has been discovered , \nReport to the Library for further discussions\n', NULL),
(59, 28, 1, '2024-11-29', 'read', NULL, 27),
(62, 29, -2, '2024-12-03', 'read', NULL, 24),
(63, 32, NULL, '2024-12-03', 'read', 'Book Return Rejected - Talk to a  Librarian for further discussions', NULL),
(66, 32, 0, '2024-12-03', 'read', NULL, 12),
(67, 33, -1, '2024-12-03', 'read', NULL, NULL),
(68, 33, -1, '2024-12-03', 'read', NULL, NULL),
(73, 35, 1, '2024-12-05', 'read', NULL, 28),
(76, 35, 1, '2024-12-05', 'read', NULL, 28),
(79, 35, 1, '2024-12-05', 'read', NULL, 28),
(84, 39, 1, '2024-12-11', 'read', NULL, 24),
(86, 25, -14, '2024-12-11', 'read', NULL, 24),
(88, 31, -3, '2024-12-11', 'read', NULL, 24),
(91, 30, -4, '2024-12-12', 'read', NULL, 24),
(92, 31, -4, '2024-12-12', 'read', NULL, 24);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `user_id`, `payment_type`, `amount`, `created_at`) VALUES
(1, 11, 1, 1201, '2024-09-05'),
(2, 12, 2, 2500, '2024-09-12'),
(3, 22, 1, 1751, '2024-09-15'),
(4, 24, 2, 150, '2024-09-20'),
(5, 25, 1, 699, '2024-09-30'),
(6, 11, 2, 50, '2024-10-01'),
(7, 12, 1, 399, '2024-10-05'),
(8, 22, 2, 50, '2024-10-10'),
(9, 24, 1, 399, '2024-10-15'),
(10, 25, 2, 399, '2024-10-25'),
(11, 11, 1, 399, '2024-11-03'),
(12, 12, 2, 25, '2024-11-08'),
(13, 22, 1, 699, '2024-11-15'),
(14, 24, 2, 125, '2024-11-20'),
(15, 25, 1, 950, '2024-11-27'),
(16, 24, 1, 399, '2024-11-28'),
(17, 24, 1, 399, '2024-11-28'),
(18, 26, 1, 399, '2024-11-29'),
(19, 26, 1, 399, '2024-11-29'),
(20, 26, 1, 399, '2024-11-29'),
(21, 26, 1, 699, '2024-11-29'),
(22, 26, 1, 699, '2024-11-29'),
(23, 24, 1, 399, '2024-12-02'),
(24, 24, 1, 699, '2024-12-03'),
(25, 24, 1, 399, '2024-12-03'),
(26, 12, 1, 399, '2024-12-03'),
(27, 28, 1, 399, '2024-12-05'),
(28, 28, 2, 25, '2024-12-05'),
(29, 28, 2, 25, '2024-12-05'),
(30, 24, 1, 399, '2024-12-08'),
(31, 24, 2, 425, '2024-12-12'),
(32, 11, 1, 399, '2024-12-12'),
(33, 11, 1, 699, '2024-12-12'),
(34, 24, 2, 1250, '2025-01-04'),
(35, 22, 1, 399, '2025-01-04');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `borrowing_period` int(11) DEFAULT NULL,
  `max_books` int(11) DEFAULT NULL,
  `fine_per_day` decimal(5,2) DEFAULT NULL,
  `features` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `name`, `price`, `borrowing_period`, `max_books`, `fine_per_day`, `features`) VALUES
(1, 'Free', 0.00, 7, 2, 50.00, NULL),
(2, 'Pro', 399.00, 14, 5, 25.00, NULL),
(3, 'Elite', 699.00, 21, 10, 0.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shelf`
--

CREATE TABLE `shelf` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shelf`
--

INSERT INTO `shelf` (`id`, `user_id`, `book_id`) VALUES
(3, 12, 2),
(5, 22, 2),
(6, 22, 6),
(9, 22, 3),
(11, 22, 1),
(18, 24, 2),
(19, 24, 9),
(20, 11, 12),
(21, 11, 2),
(22, 26, 2),
(23, 27, 3),
(24, 12, 4),
(25, 28, 6),
(29, 24, 12);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `photo` varchar(1000) NOT NULL DEFAULT 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1731586683/Screenshot_2024-11-14_201603-removebg-preview_1_mxqeaq.png',
  `phone` varchar(255) NOT NULL,
  `membership` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `plan_id` int(11) NOT NULL DEFAULT 1,
  `subscription_start` date DEFAULT NULL,
  `subscription_end` date DEFAULT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `email`, `password`, `photo`, `phone`, `membership`, `status`, `plan_id`, `subscription_start`, `subscription_end`, `created_at`) VALUES
(11, 'Phi', 'Sora', 'sora@gmail.com', '$2y$10$pAtBHL10W4t9GyNY6dBXK./sxfTVW6YUNwMAj/suCzGlCM9JMiGgO', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1731586683/Screenshot_2024-11-14_201603-removebg-preview_1_mxqeaq.png', '', '', 'Active', 3, '2024-12-12', '2025-01-12', '2024-11-14'),
(12, 'Cyrus', 'Umali Carbungco', 'umalic65@gmail.com', NULL, 'https://lh3.googleusercontent.com/a/ACg8ocLObWUoH03UfhI1-9D9jPSecUwmJJNd-fJANE02HbvlbqRBjtYw=s96-c', '', '', 'Active', 1, '2024-12-03', NULL, '2024-11-12'),
(22, 'goho', 'satory', 'gojo@gmail.com', '$2y$10$Ppdz9nBeoEQYwVHANlMnh.VYgaffEc8uCf3p6cvG/EwtiZ.KMoB26', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1731586683/Screenshot_2024-11-14_201603-removebg-preview_1_mxqeaq.png', '', '', 'Active', 2, '2025-01-04', '2025-02-04', '2024-11-19'),
(24, 'Cyrusszz', 'Umali', '0322-1935@lspu.edu.ph', NULL, 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1732450285/testUpload/dat2jellt2ata5zp16km.jpg', '09776523759', '', 'Active', 2, '2024-12-08', '2025-01-08', '2024-10-16'),
(25, 'mark', 'lopez', 'mark@gmail.com', '$2y$10$d/CL5dZwpvXQMhCQRIzOt.xl2LJHEociXsG5hCV54CUK/1yXU9xhq', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1731586683/Screenshot_2024-11-14_201603-removebg-preview_1_mxqeaq.png', '', '', 'Active', 1, NULL, NULL, '2024-10-07'),
(26, 'jayce', 'talis', 'jayce@gmail.com', '$2y$10$4lJe3Mr/29d3phjdPB/b0et5JswQtDjo4yFpUu0bn4pxeH9D/M1EO', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1731586683/Screenshot_2024-11-14_201603-removebg-preview_1_mxqeaq.png', '', '', 'Active', 1, '2024-11-29', NULL, NULL),
(27, 'swain', 'jericho', 'swain@gmail.com', '$2y$10$EQo3qeQVLB4LIQ7uGt1tC.ddf14x1ohUDhvxYuJKWe.VLJIlFWg/i', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1731586683/Screenshot_2024-11-14_201603-removebg-preview_1_mxqeaq.png', '', '', 'Active', 1, NULL, NULL, NULL),
(28, 'mike', 'lopez', 'mike@gmail.com', '$2y$10$6Oq72lo1P39DT4bwFBnA3uX6QZkCIyPYEdbUV5zT.drrrjJS8k1EC', 'https://res.cloudinary.com/dk41ykxsq/image/upload/v1731586683/Screenshot_2024-11-14_201603-removebg-preview_1_mxqeaq.png', '', '', 'Active', 1, '2024-12-05', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_contact`
--

CREATE TABLE `user_contact` (
  `id` int(100) NOT NULL,
  `cname` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `num` int(150) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_contact`
--

INSERT INTO `user_contact` (`id`, `cname`, `email`, `num`, `message`) VALUES
(0, 'asdasd', 'test@gmail.com', 23, 'xdfdsfdsfdsfds'),
(0, 'Cyrus qwe Umali', 'umalic65@gmail.com', 2147483647, 'qwewqe'),
(0, 'Cyrus qwe Umali', 'umalic65@gmail.com', 2147483647, 'qwewqeqweq'),
(0, 'Cyrus qwe Umali', 'umalic65@gmail.com', 2147483647, 'qwewqeqweq'),
(0, 'Cyrus qwe Umali', 'umalic65@gmail.com', 2147483647, 'werewr'),
(0, 'Cyrus qwe Umali', 'umalic65@gmail.com', 2147483647, 'qwewe'),
(0, 'Cyrus qwe Umali', 'umalic65@gmail.com', 2147483647, 'qwewe'),
(0, 'Cyrus qwe Umali', 'umalic65@gmail.com', 2147483647, 'wqew'),
(0, 'Cyrus qwe Umali', 'umalic65@gmail.com', 2147483647, 'qwewe'),
(0, 'Cyrus qwe Umali', 'umalic65@gmail.com', 2147483647, 'qweqwe');

-- --------------------------------------------------------

--
-- Table structure for table `user_plans`
--

CREATE TABLE `user_plans` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookcategories`
--
ALTER TABLE `bookcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookreviews`
--
ALTER TABLE `bookreviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id_fk` (`book_id`),
  ADD KEY `user_id_fk` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `load_id_fk` (`loan_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_fk` (`user_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shelf`
--
ALTER TABLE `shelf`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shelf_user_id_fk` (`user_id`),
  ADD KEY `shelf_book_id_fk` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_plans`
--
ALTER TABLE `user_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookcategories`
--
ALTER TABLE `bookcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `bookreviews`
--
ALTER TABLE `bookreviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shelf`
--
ALTER TABLE `shelf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `user_plans`
--
ALTER TABLE `user_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookreviews`
--
ALTER TABLE `bookreviews`
  ADD CONSTRAINT `bookreviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookreviews_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `book_id_fk` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `loans_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `load_id_fk` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shelf`
--
ALTER TABLE `shelf`
  ADD CONSTRAINT `shelf_book_id_fk` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shelf_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_plans`
--
ALTER TABLE `user_plans`
  ADD CONSTRAINT `user_plans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_plans_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
