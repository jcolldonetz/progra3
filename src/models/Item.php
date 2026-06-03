<?php
    class Item extends Illuminate\Database\Eloquent\Model
    {
        protected $table = 'items';
        protected $fillable = ['name','qty','price','category_id'];
        public $timestamps = true;

        // Relación con category (un item pertenece a una category)
        public function category(){
            return $this->belongsTo(Category::class);
        }
    }