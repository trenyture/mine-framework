# MINE FRAMEWORK
The PHP framework for Pure PHP Lovers!

## Technologies
- PHP 7.x
- MySQL 5.7.x
- TWIG
- HTML 5
- SASS / CSS3
- JS / jQuery

## What we should (pre)install?
- GIT [https://git-scm.com/](https://git-scm.com/)
- NodeJS [https://nodejs.org/](https://nodejs.org/)
- GULP [http://gulpjs.com/](https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md)
- COMPOSER [https://getcomposer.org/](https://getcomposer.org/)

## Framework Initialization
Well, now you can clone the repository to have the sources of the framework! ``` git clone [url_du_projet] ``` Then create a database and connect it to your framework creating the ``` /sources/configDB.json ``` file (you can have a look at the ``` /sources/configDB_base.json ``` ) and put the values of your databases like this: 

	{
		"host": "localhost",	// Server host
		"bdd": "marketplace",	// Database name
		"user": "root",			// UserName
		"pass": "",				// Password
		"env": ""				// Environnement should "prod" if on production
	}

Then we have to install framework dependencies, let's start with the Back : ``` composer install ``` and now the front : ``` npm install ```.
Well done mine-framework is now ready to use!

Now the best way to initialize your project is to use a *<virtual host>* in apache and point the *<Document Root>* to the **/public** folder of **mine-framework**. You can find a lot of good tutorial around the web.

If you don't want to use a *Virtual Host* you can put your project on your localhost and then access to it from your url : ```localhost/mine-framework/public/``` but you have to add a <base> tag in your html to point the url to ```localhost/mine-framework/public/``` to make paths and routes work.

## Explaination
So, our folder is divided in 4 important subfolders : *configs*, *public*, *resources* and *sources*. You should never modify the public folder, the config folder is just here to let you configure your environnement and then you have the two main folders, **resources** for the assets (not minified nor compiled) and then the **sources** where all the application run.

So now, I provided you a Gulp file to minify and compile your assets from */resources* to */public/assets* so just run from the command line ```gulp```. As you can see the project is made with SASS, I recommand you to use this, but if you prefer you can write pure CSS...
**Warning :** gulp will provide you a local server to automaticly reload when you change something in your assets or your sources, **you have to configure** the proxy in the gulpfile (line 22) and put your virtual host url, then it will launch the server on the **port 3000**, if you remove the port from the URL it will not reload your page!

## SOURCES
Now just focus on the **sources**, the most important part of the architecture.

- Routes -> ``` /sources/routes.php ```
- Controllers -> ``` /sources/Controllers ```
- Models -> ``` /sources/Models ```
- Managers -> ``` /sources/Managers ```
- Classes -> ``` /sources/Classes ```
- Views -> ``` /sources/Views ```

Well, if you already have develop with Object Programming in PHP, I supose I don't have to explain you, but let me explain for everyone...
I will not provide a course, just be as simple as possible : 
- First, you define the **Routes** that the application can read (with the method and the url) and you link a **Controller** Method.
- Then, when the url is called, the **Controller** will execute the Method, and exchange with your databases with the uses of the **Models** and the **Managers**, and can use generall **Classes** to play with the datas.
- **Models** are just a representation of a Table, with Getters and Setters method to hydrate them.
- **Managers** are the messengers between **Controller** and Databases, using **Models** to interprate the datas...
- **Views** are *Twig Templates* that construct all the views with the datas received from the **Controller**

## So the Architecture is :

	| - README.md
	| - .gitignore
	| - autoloader.php
	| - composer.json
	| - gulpfile.js
	| - package.json
	| - configs
		| - configDB.json
		| - configDB_base.json
	| - public
		| - index.php
		| - assets
			| - styles
			| - images
			| - scripts
			| - fonts
	| - resources
		| - sass
			| - general (contain every global vars of your css)
		| - js
		| - images
		| - fonts
	| - sources
		| - autoload.php
		| - routes.php
		| - Classes (contain every classes that your controllers can use)
			| - Database.php
			| - Middleware.php
			| - PasswordStorage.php
			| - Validator.php
		| - Controllers
			| - MyController.php
		| - Models
			| - My.php
		| - Managers
			| - MyManager.php
		| - Views
			| - MyView.html

## Copyright
**© Simon Trichereau - 2017**