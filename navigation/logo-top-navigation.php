<?php
defined( 'ABSPATH' ) or die();

/**
 * ADMIN OPTIONS HANDLE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-light navbar-light">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Navbar brand -->
        <a class="navbar-brand" href="#">Brand</a>

        <!-- Toggle button -->
        <button
                class="navbar-toggler"
                type="button"
                data-mdb-toggle="collapse"
                data-mdb-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Link -->
                <li class="nav-item"><a class="nav-link" href="#">Link</a></li>
                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            id="navbarDropdown"
                            role="button"
                            data-mdb-toggle="dropdown"
                            aria-expanded="false">
                        Dropdown
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li>
                            <hr class="dropdown-divider"/>
                        </li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Icons -->
            <ul class="navbar-nav d-flex flex-row me-1">
                <li class="nav-item me-3 me-lg-0">
                    <a class="nav-link" href="#"><i class="fa fa-shopping-cart"></i></a>
                </li>
                <li class="nav-item me-3 me-lg-0">
                    <a class="nav-link" href="#"><i class="fa fa-twitter"></i></a>
                </li>
            </ul>
            <!-- Search -->
            <form class="w-auto">
                <input
                        type="search"
                        class="form-control"
                        placeholder="Type query"
                        aria-label="Search" />
            </form>
        </div>
    </div>
    <!-- Container wrapper -->
</nav>
<!-- Navbar -->
