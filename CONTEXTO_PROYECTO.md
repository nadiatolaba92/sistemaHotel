# Contexto del Proyecto: Sistema de Gestión Hotelera

Este documento proporciona el contexto técnico, la arquitectura y la estructura de datos del proyecto `sistemaHotel`. Está diseñado para dar contexto rápido a cualquier desarrollador o agente de Inteligencia Artificial que vaya a trabajar en el código base.

## 1. Stack Tecnológico

El proyecto está desarrollado utilizando el ecosistema moderno de PHP:

*   **Lenguaje:** PHP 8.2+
*   **Framework Core:** Laravel 12
*   **Panel de Administración / Interfaz (TALL Stack):** Filament v4.0 (Tailwind CSS, Alpine.js, Laravel, Livewire). Gran parte de la UI y los ABM (CRUD) se manejan de forma declarativa a través de Filament Resources.
*   **Manejo de Archivos:** `spatie/laravel-medialibrary` integrado con Filament para la carga y asociación de imágenes a los modelos (ej. fotos de habitaciones).
*   **Frontend Assets:** Vite
*   **Testing:** Pest PHP

## 2. Arquitectura

El proyecto sigue una arquitectura **Monolítica MVC (Modelo-Vista-Controlador)** inherente a Laravel.
No obstante, el uso intensivo de **Filament** altera el flujo tradicional:
*   **Modelos:** Clases Eloquent (`app/Models/`) para la capa de acceso a datos y relaciones.
*   **Vistas y Controladores:** Se abstraen en gran medida mediante las clases `Resource` y `Pages` de Filament (`app/Filament/Resources/`), las cuales definen esquemas de formularios, tablas y lógica de presentación/control apoyadas por componentes de Livewire.

## 3. Lógica de Negocio Principal

El dominio de la aplicación es un **Property Management System (PMS)** básico para un hotel:
1.  El hotel posee **Habitaciones** físicas.
2.  Cada habitación se categoriza bajo un **Tipo** que dictamina su capacidad máxima y su precio por noche.
3.  Los clientes o huéspedes se registran como **Pasajeros**.
4.  La transacción central es la **Reserva**, que actúa como nexo entre un Pasajero y una Habitación durante un periodo de tiempo determinado (`fecha_entrada` a `fecha_salida`), registrando también el estado del pago y de la reserva misma.

## 4. Estructura de Modelos (Base de Datos)

A continuación se detallan los modelos principales en `app/Models/` y sus relaciones Eloquent:

### `Tipo` (Categoría de Habitación)
Representa las categorías de habitaciones disponibles (ej: Simple, Doble, Suite).
*   **Atributos clave:** `tipo_nombre`, `capacidad_maxima`, `precio_noche`.
*   **Relaciones:**
    *   `hasMany(Habitacione::class)` -> Una categoría engloba múltiples habitaciones.

### `Habitacione` (Habitaciones Físicas)
Representa las unidades físicas disponibles en el hotel.
*   **Atributos clave:** `habitacion_numero`, `tipo_id`, `estado`, `descripcion`.
*   **Relaciones:**
    *   `belongsTo(Tipo::class)` -> Pertenece a una categoría/tipo.
    *   `belongsToMany(Pasajero::class)` a través de la tabla pivote `reservas`.
*   **Traits adicionales:** Usa `InteractsWithMedia` y `HasMedia` (Spatie) para adjuntar imágenes.

### `Pasajero` (Huéspedes)
Almacena los datos personales de los clientes.
*   **Atributos clave:** `nombre`, `apellido`, `dni`, `telefono`, `email`.
*   **Relaciones:**
    *   `belongsToMany(Habitacione::class)` a través de la tabla pivote `reservas`.

### `Reserva` (Transacción Principal)
Administra las estancias de los huéspedes. Aunque conceptualmente es una tabla pivote entre `Pasajero` y `Habitacione`, tiene su propio modelo para facilitar su gestión.
*   **Atributos clave:** `pasajero_id`, `habitacion_id`, `fecha_entrada`, `fecha_salida`, `numero_personas`, `estado`, `tipo_pago`, `total_pagado`.
*   **Relaciones:**
    *   `belongsTo(Pasajero::class)`
    *   `belongsTo(Habitacione::class)`

---
*Nota para IAs: Al modificar código, tener en cuenta que la interfaz gráfica principal se gestiona vía Filament Panel (v4.0). Priorizar la modificación de Resources, Pages, Forms y Tables dentro del namespace `App\Filament` antes de crear vistas Blade o controladores tradicionales a menos que se requiera funcionalidad externa al panel.*
