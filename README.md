# Little Yana CMS + Admin Panel (60 kb) NO SQL

https://github.com/YanaShineRu/Little-Yana-CMS/assets/134070997/b5cf8aa3-f227-4ca5-b041-fb1590c95cbf

Settings in file settings.php
```php
<?php
return [
	// Title and metadata site
    'site_title' => 'My first blog',
    'site_description' => 'Here you will find interesting articles about different topics.',
    'site_keywords' => 'blog, articles, diversity, knowledge',

    // Name Author
    'name_author' => 'Yana',

    // Image width and height in pixels in post
    'width_img' => '200',
    'height_img' => '200',

    // Excerpt length in characters
    'excerpt_length' => 200,
];
?>
```

Password edit in file password.php

```php
<?php
$hashedPassword = password_hash('EnterYouPassword', PASSWORD_DEFAULT);

return [
    'username' => 'EnterYouLogin',
    'password' => $hashedPassword
];
?>
```
