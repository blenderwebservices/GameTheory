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
- **Gestión de Plantillas**: Acceso rápido a juegos clásicos como el Dilema del Prisionero o la Batalla de los Sexos.

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
- **Ventajas**: Notificaciones push para desafíos, portabilidad para uso en aulas, y aprovechamiento de gestos táctiles para manipular matrices y gráficos.
- **Stack Recomendado**: Flutter o React Native para mantener una base de código única con rendimiento nativo.

## 5. Modelos de Monetización
Para garantizar la sostenibilidad, se proponen modelos de bajo impacto para el usuario pero alta rentabilidad:
- **Publicidad Mínimamente Invasiva**: Banners integrados con diseño nativo que no interrumpan el flujo de análisis.
- **Suscripciones Premium**:
    - **Mensual/Anual**: Acceso a simulaciones ilimitadas, exportación de reportes PDF y análisis avanzado de N-jugadores.
    - **Lifetime (De por vida)**: Un solo pago para usuarios recurrentes que desean todas las funciones presentes y futuras.
- **Características Premium**: Temas visuales exclusivos, almacenamiento en la nube ilimitado y acceso a desafíos comunitarios especiales.

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
- **Prompt Engineering**: Sistema de "System Instructions" diseñado para forzar respuestas en formatos JSON estructurados.
- **Interfaz**: Chat interactivo lateral en la vista de simulación que manipula la matriz en tiempo real.

---

## 7. Propuesta a Corto Plazo: El Muro de Simulaciones (Estrategia de Fidelización)
Como paso inmediato para transformar la herramienta en una **comunidad colaborativa**, se propone la implementación de un "Muro de Simulaciones":

### El Valor del Contexto
Permitir que cada simulación incluya un bloque de descripción donde el autor explique su hipótesis (ej. "¿Qué pasa si el Jugador A es irracional?") y sus hallazgos.

### Estructura de la "Tarjeta de Simulación"
- **Miniatura dinámica**: Gráfico o matriz generada automáticamente.
- **Etiquetas (Tags)**: #DilemaDelPrisionero, #SumaCero, #EstrategiasEvolutivas.
- **Botón de "Remix"**: Permitir que otros carguen esa configuración exacta en su editor para modificarla.

### Gamificación y Feedback
- **Votaciones/Likes**: Para destacar el contenido más educativo.
- **Comentarios anidados**: Debate técnico profundo sobre la lógica de cada juego.
- **Desafíos**: Retos propuestos por la administración para incentivar la participación activa.

---
*Este documento constituye la hoja de ruta oficial para la evolución de Game Theory App hacia una plataforma social de referencia en el análisis estratégico.*
