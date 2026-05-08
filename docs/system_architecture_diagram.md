# AgriConnect System Architecture Diagram

This diagram presents the core architecture of the AgriConnect platform using a layered design suitable for academic capstone and project documentation.

```mermaid
graph TB
    subgraph ClientSide[Client Side (Frontend)]
        UI_Farmers[Farmer Portal\nBlade + Tailwind + Chart.js]
        UI_Buyers[Buyer Marketplace\nBlade + Tailwind + Search]
        UI_Admin[Admin Analytics\nBlade + Vite + JS]
    end

    subgraph AppLayer[Application Layer (Routing & Middleware)]
        WebRouter[Laravel Router\nWeb Routes + APIs]
        AuthMiddleware[Authentication / Authorization]
        Validation[Request Validation\nForm Requests]
        SessionMgmt[Session / CSRF Protection]
    end

    subgraph ServerLayer[Server Layer (Business Logic)]
        MatchingService[Matching Engine\napp/Services/MatchingService]
        OrderProcessor[Transaction Manager\nOrder Workflow]
        Messaging[Messaging & Notifications\nThreads / Alerts]
        Inventory[Inventory Manager\nRemainingInventory]
        Notifications[Notification Dispatcher\nEmail / Database]
    end

    subgraph DatabaseLayer[Database Layer (Persistence)]
        SQLDatabase[(SQL Database\nEloquent Models + Migrations)]
        CacheStore[(Cache / Session Store\nRedis / File / DB)]
    end

    subgraph ExternalServices[Third-Party Services]
        MediaStorage[Cloud Storage\nAWS S3 / Cloudinary]
        EmailService[SMTP / Mail Service\nTransactional Email]
    end

    UI_Farmers -->|HTTP / AJAX| WebRouter
    UI_Buyers -->|HTTP / AJAX| WebRouter
    UI_Admin -->|HTTP / AJAX| WebRouter

    WebRouter --> AuthMiddleware
    AuthMiddleware --> Validation
    Validation --> MatchingService
    Validation --> OrderProcessor
    Validation --> Messaging
    Validation --> Inventory
    Validation --> Notifications
    WebRouter --> SessionMgmt

    MatchingService -->|Read / Write| SQLDatabase
    OrderProcessor -->|Read / Write| SQLDatabase
    Messaging -->|Read / Write| SQLDatabase
    Inventory -->|Read / Write| SQLDatabase
    Notifications -->|Job Queue / Cache| CacheStore

    OrderProcessor -->|Media Upload| MediaStorage
    Notifications -->|Email Delivery| EmailService
```

## Diagram Overview

- **Client Side**: Represents user-facing interfaces for farmers, buyers, and administrators.
- **Application Layer**: Handles routing, authentication, request validation, and session control.
- **Server Layer**: Contains the business logic and service orchestration for matching, ordering, messaging, inventory, and notifications.
- **Database Layer**: Stores relational application data, session state, and caching.
- **External Services**: Manages media storage and email delivery through third-party providers.

## Key Architecture Highlights

- **Layered separation** improves maintainability and isolates responsibilities.
- **Laravel routing** provides a single entry point for both web and API traffic.
- **Service classes** like `MatchingService` encapsulate domain logic, keeping controllers thin.
- **Eloquent ORM** provides an expressive persistence layer for core entities such as `User`, `Farmer`, `Buyer`, `Product`, `Demand`, and `Transaction`.
- **External services** are used for media durability and email reliability, reducing server overhead.

## Usage

This diagram is suitable for:
- technical project documentation
- academic capstone deliverables
- architecture review slides
- system overview sections in reports
