SweetForum
==========

SweetForum it's forum plugin for CakePHP 2 based on Bootstrap 3.
![forum homepage](http://sf.saydima.com/img/github/forum.png)

**[Demo](http://sf.saydima.com/forum/)**

## Install

#### Download
* [ZIP archive](https://github.com/scofield-ua/SweetForum/archive/master.zip)

#### Installation
1. Copy files to your `app` folder
2. Create database and import [`db.sql`](https://github.com/scofield-ua/SweetForum/blob/master/db.sql)
3. Add `$sweet_forum` to your `DATABASE_CONFIG` class in `database.php`
4. Change `SWEET_FORUM_BASE_URL` constant in `SweetForum/Config/bootstrap.php` (it's will be path to your forum homepage)
5. Set `sweet_forum` folder rights in `app/tmp` to 777
6. You have to comment `_cake_core_` config definition in your `Config/core.php` file. It's seems like some conflicts with path information between two plugins (CakePHP problem)
![cake_core](http://sf.saydima.com/img/github/cake_core.png)

## Multilanguage
Now supports:
* English 
* Russian

## Used
* [Bootstrap](http://getbootstrap.com/)
* [Venobox] (http://lab.veno.it/venobox/)
