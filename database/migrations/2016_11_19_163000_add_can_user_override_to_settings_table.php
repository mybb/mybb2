<?php
/**
 * Add can_user_override column.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/settings
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCanUserOverrideToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('can_user_override')->default(false);
            $table->string('setting_type')->default('string');
            $table->string('default_value')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('can_user_override');
            $table->dropColumn('setting_type');
            $table->dropColumn('default_value');
        });
    }
}
