SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Struttura della tabella `browser_scraping`
--

CREATE TABLE IF NOT EXISTS `airs_browser_scraping` (
  `uri` varchar(500) NOT NULL COMMENT 'URI',
  `user` varchar(16) NOT NULL COMMENT 'User that launched operation',
  `data` date NOT NULL COMMENT 'Date',
  `ora` time NOT NULL COMMENT 'Hour'
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8 COMMENT='Scraped web pages';
