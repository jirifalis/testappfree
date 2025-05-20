# Frontend

## Komunikace se serverem

Pro komunikaci se serverem jsem udělal několik služeb extendující `BaseClient`

- `ResourceApiService`: načítá seznam resources
- `UserApiService`: načítá a aktualizuje uživatele
- `GroupApiService`: načítá, vytváří a aktualizuje skupiny

## Routování

| Cesta         | Name        | Popis                  |
|---------------|-------------|------------------------|
| /             | Index       | Hlavní stránka         |
| /users        | Users       | Seznam uživatelů       |
| /user/:id     | User        | Detail uživatele       |
| /groups       | Groups      | Seznam skupin          |
| /group/:id    | Group       | Detail skupiny         |
| /group/create | CreateGroup | Vytvoření nové skupiny |

## Komponenty

### TableViewComponent

Připravil jsem návrh na globální komponentu pro zobrazování listu. Do budoucna bych nad touto komponentou přidal stránkování, filtry, a další.

### SaveButton

Vytvořil jsem globální komponentu SaveButton, která řeší ukládání a zobrazení stavu uživateli.

| Stav      | Vzhled                                                                                                                |
|-----------|-----------------------------------------------------------------------------------------------------------------------|
| Normal    | <button>save</button>                                                                                                 |
| Saving... | <button>save</button><span style="margin-left:10px; color:green">⏳</span>                                             |
| Saved     | <button>save</button><span style="margin-left:10px; color:green">👍</span>                                            |
| Error     | <button>save</button><span style="margin-left:10px; color:red">❌ Failed to fetch data: HTTP error! status: 500</span> |

