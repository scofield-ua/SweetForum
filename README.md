SweetForum
==========

Demo: http://sf.saydima.com/forum/

## Install

#### Download
* [SweetForum and SweetForumAdmin](https://github.com/scofield-ua/SweetForum/archive/master.zip)

#### Installation
1. Copy SweetForum and SweetForumAdmin to app Plugin folder
2. Create database and import [`db.sql`](https://github.com/scofield-ua/SweetForum/blob/master/db.sql)
3. Add `$sweet_forum` to your `DATABASE_CONFIG` class in `database.php`
4. Change `SWEET_FORUM_BASE_URL` constant in `SweetForum/Config/bootstrap.php` (it's will be path to your forum homepage)
5. Create `sweet_forum` folder in `app/tmp` and set it rights to 777
6. You have to comment `_cake_core_` config definition in your `Config/core.php` file. It's seems like some conflicts with path information between two plugins (CakePHP problem)
![cake_core](http://sf.saydima.com/img/github/cake_core.png)
