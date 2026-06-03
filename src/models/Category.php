<?php
    // Definir la clase Category que extiende de Eloquent Model
    class Category extends Illuminate\Database\Eloquent\Model
    {
        protected $table = 'categories';
        protected $fillable = ['name', 'description'];
        public $timestamps = true;

        // Relación con items (un category tiene muchos items)
        public function items(){
            return $this->hasMany(Item::class);
        }
    }