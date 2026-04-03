<?php 

// 1. URL de prueba usando la IP y el puerto 8443
const SERVERURL = "https://10.1.2.16:8443/";
const COMPANY="Registro Usuarios (PRUEBA)";
date_default_timezone_set("America/Argentina/Buenos_Aires");

// 2. Conexión a la base de datos de prueba
const SERVER= "mysql_db_test"; 
const DB= "boletas-data";
const USER= "root";
const PASS= "Grut4.";

const SGBD = "mysql:host=".SERVER.";dbname=".DB;

// CONFIGURACIONES PARA LA ENCRIPTACIÓN (NO TOCAR)
const METHOD="AES-256-CBC";
const SECRET_KEY='$MVC@1983';
const SECRET_IV='208319';
