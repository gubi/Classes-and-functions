# Class Captcha
This class generates a simple computation captcha image with validation through cookie.  
The result image is different from others due to own particularities: numbers and factors are composed by micro-letters, then coloured and distorted with noises lines...  
Only a human that moving back head from screen can read it!


![Captcha example](https://raw.github.com/gubi/Classes-and-functions/master/Captcha/captcha.png "Captcha example")

----


### Before Install
Please, make sure that font file "miriam_fixed.ttf" is in the same directory of script.

#### Dependancies
Script requires Php-GD extension.  
You can follow documentation here: http://php.net/manual/en/book.image.php

----


### Instruction of use
Simply upload the script with the font file in your preferred directory.  
Then, in your form, call the script as image in the src attribute of image tag:
```HTML
<img src="capcha.php" alt="Captcha image" />
```

So create an input tag for the result of calculation:
```HTML
<input type="number" name="captcha_result" size="3" maxlength="2" min="0" max="99" />
```

And compares the user input with cookie.  
**Do not store cookie value in a hidden form: robots will can read it!**

### Hack
You can prevent caching passing random digits as GET data, like this:
```HTML
<img src="capcha.php*?0983747839398372992029183" alt="Captcha image" />
```

----


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