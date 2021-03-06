<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminux extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins_languages', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->char('language', 5)->default('')->unique();
            $table->softDeletes();
            $table->timestamps();
        });
        DB::table('admins_languages')->insert([
            ['language' => 'en', 'created_at' => now()],
            ['language' => 'es', 'created_at' => now()],
        ]);


        Schema::create('admins', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('email', 75)->default('')->unique();
            $table->string('password')->default('');
            $table->string('firstname', 75)->default('');
            $table->string('lastname', 75)->default('');
            $table->smallInteger('language_id')->unsigned();
            $table->foreign('language_id')->references('id')->on('admins_languages');
            $table->enum('superuser', ['N', 'Y'])->default('N');
            $table->enum('active', ['N', 'Y'])->default('N');
            $table->rememberToken();
            $table->string('last_login_ip', 75)->default('');
            $table->timestamp('last_login_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        DB::table('admins')->insert([
            'email'       => 'admin@localhost',
            'password'    => '$2y$10$JhK7HP96YDXBQ3Twcr5EBe4ePGtPcA3OZbd5Ef9LTpmOfWSpy9H..', // test
            'language_id' => 1,
            'superuser'   => 'Y',
            'active'      => 'Y',
            'created_at'  => now()
        ]);


        Schema::create('partners', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('partner', 100)->default('')->unique();
            $table->smallInteger('language_id')->unsigned();
            $table->foreign('language_id')->references('id')->on('admins_languages');
            $table->text('module_config')->nullable()->comment('json config/properties');
            $table->enum('active', ['N', 'Y'])->default('N');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::table('partners')->insert([
            'partner'    => 'Adminux',
            'language_id' => 1,
            'active'     => 'Y',
            'created_at' => now()
        ]);


        Schema::create('admin_partner', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->mediumInteger('admin_id')->unsigned();
            $table->foreign('admin_id')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
            $table->mediumInteger('partner_id')->unsigned();
            $table->foreign('partner_id')->references('id')->on('partners')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp('created_at')->nullable();
            $table->unique(['admin_id', 'partner_id'], 'admin_partner');
        });
        DB::table('admin_partner')->insert([
            'admin_id'   => 1,
            'partner_id' => 1,
            'created_at' => now()
        ]);


        Schema::create('admins_currencies', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->char('currency', 3)->default('')->unique();
            $table->softDeletes();
            $table->timestamps();
        });
        DB::table('admins_currencies')->insert([
            ['currency' => 'USD', 'created_at' => now()]
        ]);


        Schema::create('software', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('software', 100)->default('')->unique();
            $table->string('software_class', 255)->nullable()->comment('App\Adminux\Software\Controllers ...');
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('software_features', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->mediumInteger('software_id')->unsigned();
            $table->foreign('software_id')->references('id')->on('software');
            $table->string('feature', 100)->default('');
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('services', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->mediumInteger('partner_id')->unsigned();
            $table->foreign('partner_id')->references('id')->on('partners');
            $table->mediumInteger('software_id')->unsigned();
            $table->foreign('software_id')->references('id')->on('software');
            $table->string('service', 100)->default('');
            $table->string('domain', 255)->nullable();
            $table->smallInteger('currency_id')->unsigned();
            $table->foreign('currency_id')->references('id')->on('admins_currencies');
            $table->decimal('price', 10, 3)->default(0)->unsigned();
            $table->enum('interval', ['perunit', 'daily', 'monthly ', 'yearly', 'onetime'])->default('monthly');
            $table->text('price_history')->nullable()->comment('json old prices');
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('services_plans', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->mediumInteger('service_id')->unsigned();
            $table->foreign('service_id')->references('id')->on('services');
            $table->string('plan', 100)->default('');
            $table->smallInteger('currency_id')->unsigned();
            $table->foreign('currency_id')->references('id')->on('admins_currencies');
            $table->decimal('price', 10, 3)->default(0)->unsigned();
            $table->enum('interval', ['perunit', 'daily', 'monthly ', 'yearly', 'onetime'])->default('monthly');
            $table->text('price_history')->nullable()->comment('json old prices');
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('accounts', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->mediumInteger('partner_id')->unsigned();
            $table->foreign('partner_id')->references('id')->on('partners');
            $table->string('email', 75)->default('')->index();
            $table->string('password')->default('');
            $table->string('account', 75)->nullable();
            $table->smallInteger('language_id')->unsigned();
            $table->foreign('language_id')->references('id')->on('admins_languages');
            $table->enum('active', ['N', 'Y'])->default('Y');
            $table->rememberToken();
            $table->string('last_login_ip', 75)->default('');
            $table->timestamp('last_login_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['partner_id', 'email'], 'partner_email');
            $table->unique(['partner_id', 'account'], 'partner_account');
        });


        Schema::create('accounts_products', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->mediumInteger('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->mediumInteger('plan_id')->unsigned();
            $table->foreign('plan_id')->references('id')->on('services_plans');
            $table->mediumText('software_config')->nullable()->comment('json config/properties');
            $table->enum('active', ['N', 'Y'])->default('N');
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('billing_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->mediumInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('accounts_products')->onDelete('cascade')->onUpdate('cascade');
            $table->date('date')->nullable();
            $table->integer('units')->unsigned()->default(0);
            $table->unique(['date', 'product_id'], 'product_date');
            $table->timestamps();
        });


        Schema::create('billing_usage', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->mediumInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('accounts_products')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('date_start')->useCurrent();
            $table->timestamp('date_end')->nullable();
        });

        DB::unprepared('CREATE TRIGGER accounts_products_after_insert AFTER
            INSERT ON accounts_products FOR EACH ROW
            IF(NEW.active = "Y") THEN
                INSERT INTO billing_usage (product_id)
                VALUES (NEW.id);
            END IF;');

        DB::unprepared('CREATE TRIGGER accounts_products_after_update AFTER
            UPDATE ON accounts_products FOR EACH ROW
            IF(NEW.active = "Y") THEN
            	IF((SELECT product_id FROM billing_usage WHERE product_id = NEW.id LIMIT 1) IS NULL) THEN
            		INSERT INTO billing_usage (product_id) VALUES (NEW.id);
            	ELSE
					IF((SELECT COUNT(*) - SUM(IF(date_end IS NULL, 0, 1)) FROM billing_usage WHERE product_id = NEW.id) = 0) THEN
        			    INSERT INTO billing_usage (product_id) VALUES (NEW.id);
            		END IF;
            	END IF;
            ELSE
            	IF((SELECT product_id FROM billing_usage WHERE product_id = NEW.id AND date_end IS NULL LIMIT 1) IS NOT NULL) THEN
            		UPDATE billing_usage SET date_end = CURRENT_TIMESTAMP() WHERE product_id = NEW.id AND date_end IS NULL;
            	END IF;
            END IF');


        Schema::create('tickets_categories', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
            $table->string('color', 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('tickets_priorities', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
            $table->string('color', 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('tickets_statuses', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
            $table->string('color', 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('tickets', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->mediumInteger('admin_id')->unsigned()->nullable();
            $table->foreign('admin_id')->references('id')->on('admins');
            $table->mediumInteger('account_id')->unsigned()->nullable();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->mediumInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('accounts_products');
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->smallInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('tickets_categories');
            $table->smallInteger('priority_id')->unsigned();
            $table->foreign('priority_id')->references('id')->on('tickets_priorities');
            $table->smallInteger('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('tickets_statuses');
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('tickets_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumInteger('ticket_id')->unsigned();
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->mediumInteger('admin_id')->unsigned()->nullable();
            $table->foreign('admin_id')->references('id')->on('admins');
            $table->mediumInteger('account_id')->unsigned()->nullable();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('accounts_products');

        Schema::dropIfExists('billing_units');
        Schema::dropIfExists('billing_usage');

        Schema::dropIfExists('admins');
        Schema::dropIfExists('admins_currencies');
        Schema::dropIfExists('admins_languages');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('admin_partner');

        Schema::dropIfExists('services');
        Schema::dropIfExists('services_plans');
        Schema::dropIfExists('software');
        Schema::dropIfExists('software_features');

        Schema::dropIfExists('tickets');
        Schema::dropIfExists('tickets_categories');
        Schema::dropIfExists('tickets_priorities');
        Schema::dropIfExists('tickets_statuses');
        Schema::dropIfExists('tickets_comments');
    }
}
