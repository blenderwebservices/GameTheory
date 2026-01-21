# Teoría de Juegos: Equilibrio de Nash en Juegos No Cooperativos

Esta aplicación es una herramienta didáctica diseñada para explorar y comprender el **Equilibrio de Nash** en el contexto de la Teoría de Juegos No Cooperativos.

## ¿Qué hace esta aplicación?
Basada en el concepto matemático desarrollado por John Nash, esta app permite a los usuarios estudiar situaciones estratégicas donde:
- Hay dos o más jugadores (en nuestros modelos básicos, principalmente 2x2).
- Cada jugador actúa de forma independiente para maximizar su propio beneficio.
- No existen acuerdos vinculantes entre los participantes (Juegos No Cooperativos).

El objetivo didáctico es demostrar cómo se alcanzan estados estables (equilibrios) en los que ningún jugador tiene incentivos para cambiar su estrategia unilateralmente, dado lo que hacen los demás. Esto ayuda a ilustrar fenómenos económicos y sociales, como el dilema del prisionero, donde la búsqueda del interés propio no siempre lleva al óptimo del grupo.

## Funcionalidades y Simulaciones
La aplicación ofrece un entorno interactivo para:
1.  **Simular Modelos**: Los usuarios pueden interactuar con matrices de pagos (payoff matrices).
2.  **Calcular Equilibrios**: Al ingresar o modificar los valores de utilidad para cada estrategia, la app identifica automáticamente las mejores respuestas y resalta las celdas que constituyen un Equilibrio de Nash.
3.  **Experimentar**: Se puede observar en tiempo real cómo cambios en los incentivos afectan el resultado del juego.

## Cómo utilizar la App
Para garantizar una experiencia personalizada y permitir el guardado de simulaciones:
1.  **Registro**: Es necesario crear una cuenta nueva a través del formulario de registro.
2.  **Inicio de Sesión (Login)**: Una vez registrado, debe iniciar sesión con sus credenciales.
3.  **Navegación**: Tras loguearse, podrá acceder al panel de simulaciones y comenzar a crear o editar sus propios escenarios de juego.

## Pasos a seguir (Roadmap)
El proyecto se encuentra en constante evolución. Las siguientes características y mejoras están planificadas para futuras versiones:

*   **API para Mobile**: Construcción de una API RESTful robusta para dar soporte a una futura aplicación móvil nativa.
*   **Simulaciones Avanzadas**: Capacidad para simular matrices de mayores dimensiones ($N \times M$) y con más estrategias, superando el modelo básico de 2x2.
*   **Monetización**: Integración de publicidad mediante Google Adwords para la sostenibilidad del proyecto.
*   **Escalabilidad de Base de Datos**: Migración planificada de la base de datos actual a motores más robustos como **MySQL** o **PostgreSQL** para manejar un mayor volumen de usuarios y simulaciones.

## Arquitectura del Proyecto
Esta aplicación ha sido construida utilizando un stack tecnológico moderno y robusto, preparado para escalar:

### Backend & Frameworks
*   **PHP**: Versión 8.2+
*   **Laravel**: Versión 12.0
*   **Livewire**: Versión 3.x (Motor reactivo full-stack)
*   **Filament**: Versión 3.2 (Panel de administración y constructor de formularios)

### Frontend & Construcción
*   **Node.js**: Entorno de ejecución para herramientas de frontend.
*   **Vite**: Versión 7.x (Empaquetador de módulos rápido)
*   **TailwindCSS**: Versión 4.0 (Framework de utilidades CSS)

### Base de Datos
*   **Desarrollo/Actual**: SQLite
*   **Producción (Futuro)**: MySQL / PostgreSQL

### Infraestructura de Servidor
*   **Hospedaje**: VPS en Ionos gestionado con Plesk.
*   **Sistema Operativo**: CentOS 8.

---
*Explora la dinámica de las decisiones estratégicas y comprende por qué "hacer lo mejor para uno mismo" depende crucialmente de lo que hagan los demás.*
