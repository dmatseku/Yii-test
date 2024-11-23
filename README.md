# Yii Test Task

## Setup

1. Clone project and create database. Link apache/nginx to `backend/web` folder.
2. Setup file `common/config/main-local.php` with the following code:
    ```
    <?php
    
    return [
        'components' => [
            'db' => [
                'class' => \yii\db\Connection::class,
                'dsn' => 'mysql:host=<host>;dbname=<database name>',
                'username' => '<username>',
                'password' => '<password>',
                'charset' => 'utf8',
            ],
        ],
    ];
    ```
3. Run the setup script: `sh local_init.sh` and agree with everything.
4. Register cron task like the following `@yearly <path_to_project>/php yii annual/lucky`

## API and roles

All api requests are covered by roles. There are two roles by default: client and admin. Client can only create own profile or loan and view only own data. To assign user to admin role, use the next command: `php yii rbac/admin <user_id>`.
All three entities (User, File and Loan) have the default crud implemented. Also, they have special actions described here: <p href="https://app.swaggerhub.com/apis/DMATSEKU/your-api/1.0.0-oas3">https://app.swaggerhub.com/apis/DMATSEKU/your-api/1.0.0-oas3</p>

## Structure

The most of the implementation is located in `backend` module; DB configuration is located in `common` module; migrations and commands are located in `console` module.
Mostly it's a controller-form-model or controller-crud implementation with `backend\Loan\LoanService` as an example. to work with entities in my own code the Repositories were used anywhere. They are located in the `model` folder and implement the interfaces in `contracts` folder.