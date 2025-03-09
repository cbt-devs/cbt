<?php
// Navigation.php

class Navigation {
    public function getLink( $page ) {
        return "modules/{$page}.php";
    }
}