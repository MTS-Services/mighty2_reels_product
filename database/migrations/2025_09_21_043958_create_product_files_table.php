<?php

use App\Livewire\Backend\Product;
use App\Models\productFile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    use SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_files', function (Blueprint $table) {
           
            $table->id();
            $table->bigInteger('sort_order')->default(0);

            $table->unsignedBigInteger('product_id');
            $table->string('file');
            $table->tinyInteger('file_type')->default(ProductFile::TYPE_IMAGE)->comment(ProductFile::TYPE_IMAGE . ' = Image, ' . ProductFile::TYPE_REEL . ' = Reel');
            $table->tinyInteger('status')->default(ProductFile::STATUS_ACTIVE)->comment(ProductFile::STATUS_ACTIVE . ' = Active, ' . ProductFile::STATUS_INACTIVE . ' = Inactive');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
            $table->softDeletes();
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_files');
    }
};
