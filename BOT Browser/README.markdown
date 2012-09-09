# Function BOT Browser
This function scan a defined URI with random User Agent and returns an array with some data or its content.  
It save also a log in a database.  

----

#### Dependancies
Script requires:
* **cURL extension**  
documentation here: http://www.php.net/manual/en/book.curl.php
* **PDO extension** (documentation here: http://www.php.net/manual/en/book.pdo.php)

----

### Instruction of use
1. Import databases UAS and system_logs to your mysql database;
2. Include browser.php in your script and call  
```Php
browse(string = URI, string = USER, [param = "header", "mixed", ""]);
```



## License
    #  License
    #  
    #  Copyright (C) 2012  Alessandro Gubitosi
    #  
    #  This program is free software: you can redistribute it and/or modify
    #  it under the terms of the GNU General Public License as published by
    #  the Free Software Foundation, either version 3 of the License, or
    #  (at your option) any later version.
    #  
    #  This program is distributed in the hope that it will be useful,
    #  but WITHOUT ANY WARRANTY; without even the implied warranty of
    #  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    #  GNU General Public License for more details.
    #  
    #  You should have received a copy of the GNU General Public License
    #  along with this program.  If not, see <http://www.gnu.org/licenses/>.
    #
    #
    # Lecense URI: http://www.gnu.org/licenses/gpl-3.0.txt
    #
    #    

This application is released under the Free GNU General Public License v3.0.
For more information about GNU License, see http://www.gnu.org/licenses/gpl.html