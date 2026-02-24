# Proyecto: Game Theory App - Visión y Roadmap

## 1. Descripción y Enfoque Didáctico
La **Game Theory App** es una herramienta educativa diseñada para democratizar el aprendizaje de la teoría de juegos. Su enfoque es puramente **didáctico y experimental**, permitiendo a estudiantes, académicos y entusiastas modelar situaciones de conflicto y cooperación en un entorno controlado y visual.

El objetivo no es solo resolver algoritmos, sino permitir al usuario "sentir" las tensiones de la racionalidad a través de la simulación de escenarios clásicos y personalizados.

## 2. Cota Actual de Funcionalidad
Hoy, la plataforma permite:
- **Modelado de Matrices de Pagos**: Configuración flexible de recompensas para dos jugadores.
- **Cálculo de Equilibrios de Nash**: Identificación automática de equilibrios puros y mixtos.
- **Análisis de Estrategias Dominantes**: Explicación lógica de por qué una decisión prevalece sobre otra.
- **Interacción Social Básica**: Sistema de comentarios por escenario para debate técnico.
- **Portabilidad de Datos (Fase Inicial)**: Exportación de simulaciones para respaldo.
- **Gestión de Plantillas**: Acceso rápido a juegos clásicos.

## 3. Plataforma Tecnológica

### Estado Actual
La aplicación está construida sobre un stack moderno y ágil:
- **Backend**: Laravel PHP (Robustez y rapidez de desarrollo).
- **Admin & UI**: FilamentPHP + Livewire (Interfaces reactivas sin salir del ecosistema PHP).
- **Frontend**: TailwindCSS + AlpineJS (Estética premium y micro-interacciones eficientes).

### Visión Futura: Robustez y Escalabilidad
Para escalar a una audiencia global, la evolución natural hacia una plataforma **sólida y escalable** propone:
- **Arquitectura**: Migración hacia una arquitectura de API desacoplada.
- **Frontend**: Framework sofisticado como React o Vue.js para una experiencia de usuario tipo SPA (Single Page Application) fluida.
- **Servicios**: Implementación de motores de cálculo en lenguajes de alto rendimiento (como Go o Python) para simulaciones complejas de N-jugadores.

## 4. Desarrollo Mobile y Ubicuidad
El salto a **App Mobile** es fundamental para la fidelización.
- **Ventajas**: Notificaciones push para desafíos, alertas de nuevos seguidores, likes y portabilidad para uso en aulas.
- **Stack Recomendado**: Flutter o React Native para mantener una base de código única con rendimiento nativo.

## 5. Modelos de Monetización
Para garantizar la sostenibilidad, se proponen modelos de bajo impacto para el usuario pero alta rentabilidad:
- **Publicidad Mínimamente Invasiva**: Banners integrados con diseño nativo que no interrumpan el flujo de análisis.
- **Suscripciones Premium**:
    - **Mensual/Anual**: Acceso a simulaciones ilimitadas, exportación de reportes PDF y análisis avanzado de N-jugadores.
    - **Lifetime (De por vida)**: Un solo pago para usuarios recurrentes que desean todas las funciones presentes y futuras.
- **Características Premium**: 
    - **Reportes Profesionales (PDF)**: Generación de informes estéticos con branding personalizado, ideal para presentaciones académicas o profesionales.
    - **Análisis N-Jugadores**: Motores avanzados para juegos más complejos.
    - **Personalización Visual**: Temas exclusivos y almacenamiento ilimitado.

---

## 6. Evolución a Plataforma de Consultoría Estratégica (Integración de I.A.)
El salto definitivo para la plataforma es la integración de Inteligencia Artificial, transformando la app de un repositorio teórico a un **copiloto estratégico**.

### Nivel 1: Traductor de Escenarios (Funcional)
- **Objetivo**: Convertir lenguaje natural en matrices estructuradas.
- **Flujo**: El usuario describe un conflicto de negocio ("Mi competidor bajó precios..."). La I.A. identifica jugadores, estrategias y estima los *payoffs* iniciales.
- **Resultado**: Generación automática de la matriz $2 \times 2$ lista para simular.

### Nivel 2: Simulador de Racionalidad (Analítico)
- **Análisis de Racionalidad**: La I.A. actúa como consultor, explicando por qué se alcanza (o no) un equilibrio.
- **Escenarios "What If"**: El usuario puede preguntar: "¿Qué pasa si mi competidor es irracional y busca venganza?". La I.A. recalibra la matriz basándose en utilidades psicológicas.
- **Análisis de Estabilidad**: Sugerencias proactivas sobre cómo cambiar las reglas del juego para favorecer al usuario.

### Nivel 3: Matrices Dinámicas y Complejas (Experto)
- **Árboles de Decisión**: Generación de juegos en forma extensa (secuenciales) mediante conversación.
- **Optimización Basada en Datos**: Sugerencia de valores de pago basados en bibliografía, casos de estudio históricos o datos económicos reales.

### Implementación Técnica de I.A.
- **Motor**: Integración vía API con modelos como Gemini 1.5 Pro o GPT-4.
- **Personalización (BYOK)**: Opción para que el usuario integre su propia API Key de LLM, permitiendo un uso personalizado y control de costos/privacidad.
- **Prompt Engineering**: Sistema de "System Instructions" diseñado para forzar respuestas en formatos JSON estructurados.
- **Interfaz**: Chat interactivo lateral en la vista de simulación que manipula la matriz en tiempo real.

---

## 7. Propuesta a Corto Plazo: El Muro de Simulaciones (Estrategia de Fidelización)
Como paso inmediato para transformar la herramienta en una **comunidad colaborativa**, se propone la implementación de un "Muro de Simulaciones":

### Dinámica Temporal y UX (Muro Vivo)
- **Orden Cronológico Inverso**: Las simulaciones más recientes aparecen siempre en la parte superior, garantizando frescura visual constante.
- **Límite de Exposición (Feed Curado)**: El muro principal muestra un número limitado de modelos (ej. los últimos 50-100) para mantener un rendimiento óptimo y una experiencia de usuario (UX) ligera y agradable.
- **Histórico Completo**: Los modelos que "desaparecen" del feed principal por antigüedad siguen siendo parte de la base de conocimientos y son accesibles mediante la búsqueda.

### Descubrimiento y Búsqueda Avanzada
Un cuadro de búsqueda inteligente permitirá ubicar cualquier experimento, incluso aquellos fuera del feed principal, filtrando por:
- **Autor**: Búsqueda por ID de usuario o nombre.
- **Título**: Localización por palabras clave en el nombre del modelo.
- **Etiquetas (Tags)**: Filtrado por categorías técnicas (#EquilibrioNash, #SumaCero).
- **Temporalidad**: Búsqueda por rangos de fecha de creación o publicación.

### Estructura de la "Tarjeta de Simulación"
- **Identificador Visual (Iconografía)**: Posibilidad de asignar un icono personalizado a color desde una biblioteca integrada (ej. Heroicons, FontAwesome) para identificar rápidamente el tipo de problema o industria del modelo.
- **Miniatura dinámica**: Gráfico o matriz generada automáticamente.
- **Botón de "Remix"**: Permitir que otros carguen esa configuración exacta en su editor para modificarla.

### Gamificación y Feedback
- **Votaciones/Likes**: Para destacar el contenido más educativo y validar el ingenio de los creadores.
- **Sistema de Seguimiento**: Capacidad de seguir a autores específicos del muro para recibir notificaciones de sus nuevas publicaciones.
- **Portabilidad y Colaboración Técnica**:
    - **Exportación/Importación JSON**: Capacidad de descargar la estructura de un juego en formato JSON para compartirlo fuera de la plataforma o realizar respaldos manuales. Permite la "inyección" de escenarios complejos creados por terceros.
    - **Remix desde Archivo**: Opción de cargar un archivo JSON directamente al editor para iniciar una simulación instantánea.
- **Comentarios anidados**: Debate técnico profundo sobre la lógica de cada juego.
- **Notificaciones Inteligentes**:
    - **Desde Administración**: Comunicados globales, nuevos desafíos o actualizaciones críticas.
    - **Interacción Social**: Alertas por nuevos seguidores, likes recibidos o comentarios en simulaciones propias.
- **Desafíos**: Retos propuestos por la administración para incentivar la participación activa.

---
*Este documento constituye la hoja de ruta oficial para la evolución de Game Theory App hacia una plataforma social de referencia en el análisis estratégico.*
