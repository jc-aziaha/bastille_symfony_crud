<?php

    trait Utils
    {
        public function isArtist(): bool
        {
            return true;
        }
    }

    class Auteur
    {
        use Utils;


    }

    class Peintre
    {

    }


    // interface Actions
    // {
    //     public function demarrer();
    //     public function rouler();
    //     public function freiner();
    // }

    // abstract class Vehicule implements Actions
    // {

    // }

    // class Voiture extends Vehicule
    // {
    //     public function demarrer()
    //     {

    //     }

    //     public function rouler()
    //     {

    //     }

    //     public function freiner()
    //     {

    //     }
    // }

