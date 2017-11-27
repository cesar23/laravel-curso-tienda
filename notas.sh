#generacion de modelos

php artisan make:model Category -m
php artisan make:model Product -m


php artisan make:model Guest -m
php artisan make:model Categorie -m
php artisan make:model Store -m
php artisan make:model StoresCategorie -m
php artisan make:model Local -m
php artisan make:model Card -m

php artisan make:model CreditCard -m

php artisan make:model Order -m -r #generacion de modelo y controller

php artisan make:model ClientsCard -m
php artisan make:model ClientsCardSend -m

php artisan make:model Favorite -m
php artisan make:model Notification -m
php artisan make:model NotificationStore -m

php artisan make:model UsersStore -m

php artisan make:model UsersLocal -m

php artisan make:model Banner -m -r #generacion de modelo y controller
php artisan make:model Post -m -r #generacion de modelo y controller

php artisan make:model Country -m


#------------------------------------seeed
php artisan make:seeder UsersTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder ClientsTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder GuestsTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder CategoriesTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder StoresTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder LocalsTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder CardsTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder OrdersTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder CreditCardsTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder ClientsCardsTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder ClientsCardSendsTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder FavoritesTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder NotificationsTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder UsuariosStoreTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder UsuarioLocalTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder BannersTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder PostsTableSeeder && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder CountriesTableSeeder && ping 127.0.0.1 -n 2 > nul


  php artisan make:seeder CategoryTableSeeder  && ping 127.0.0.1 -n 2 > nul
  php artisan make:seeder ProductTableSeeder   && ping 127.0.0.1 -n 2 > nul

 #-----------------------------------------------------------------
 #-----------Crear Middleware
 #-----------------------------------------------------------------
 php artisan make:middleware IsSuperAdmin
 php artisan make:middleware IsStore
 php artisan make:middleware IsLocal


 #-----------------------------------------------------------------
 #----------- http://ovedfs.com/05-carrito-de-compras-taller-de-desarrollo-de-una-tienda-en-linea/
 #-----------Crear Helper    fuente:https://leonelomar.wordpress.com/2016/04/29/crear-helpers-personalizados-en-laravel/
 #-----------------------------------------------------------------

C:\xampp\htdocs\giftcardfinal

php artisan make:controller  WebServiceController -r

php artisan make:controller  StoreController -r
php artisan make:controller  CardController -r