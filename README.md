# IdentificaTest

Repositorio con 3 proyectos:

    -Plantilla sistema de corrección de examen
    -Sorteo pizarra y reunión
    -Proyecto IdentificaTest (Proyecto Fin Ciclo)

En este proyecto se utiliza LAMP con Symfony 4.

Pasos para la instalación del proyecto:


    - Instalacion de Apache2, MYSQL, PHP **`7.1`**,
    - git clone del proyecto,
    - cd a la carpeta del proyecto,
    - seguir las instrucciones de https://getcomposer.org/download/
    - composer install para instalar todas las dependencias 
        que hay en el composer.lock,
    - levantar servidor con el comando:
        php bin/console server:run
    - las rutas del proyecto de fin de ciclo son:
        - /home
        - /encuestas
        - /encuesta/{id}
        - /sorteo
        - /sorteo/login
        

Opcional: 
    
    - En caso de tener problemas importando base de datos,
      ejecutar los siguientes comandos para crear base de datos
      de prueba:
            - php bin/console doctrine:database:create
            - php bin/console doctrine:schema:update --force
            - php bin/console doctrine:fixtures:load
    
    

En caso de problemas, consultar los siguientes links:

https://symfony.com/doc/current/reference/requirements.html

https://symfony.com/doc/current/setup.html

https://getcomposer.org

https://geekytheory.com/linux-como-instalar-lamp
