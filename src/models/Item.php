<?php
    class Item extends Illuminate\Database\Eloquent\Model
    {
        protected $table = 'items';
        protected $fillable = ['name', 'qty','price'];
        public $timestamps = true;
    }