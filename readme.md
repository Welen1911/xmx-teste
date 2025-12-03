# XMX Blog

## 📌 Descrição
Projeto desenvolvido para o teste técnico, consistindo em um blog integrado à API DummyJSON, realizando consumo, persistência local e exibição dos dados em uma interface moderna utilizando Laravel + Inertia + Vue 3.

O sistema apresenta posts, comentários, usuários e páginas específicas para cada usuário.

---

## 🚀 Tecnologias Utilizadas

- **Laravel 12.x**
- **MySQL**
- **Inertia.js 2**
- **Vue.js 3**
- **TailwindCSS**
- **Shadcn-Vue**
- **PHPUnit** (testes automatizados)

---

## 📂 Funcionalidades do Projeto

### ✅ 1. Página Inicial (/)
- Listagem paginada (30 por página)
- Exibição:
  - Título
  - Tags
  - Contadores de like/dislike
  - Total de comentários
- Ações:
  - Acessar detalhes do post

### ✅ 2. Detalhes do Post (/post/{id})
- Título
- Conteúdo completo
- Tags
- Contadores de interação
- Número de visualizações
- Listagem de comentários:
  - Nome do usuário
  - Conteúdo do comentário

### ✅ 3. Posts do Usuário (/user/{id}/posts)
- Listagem de posts do usuário
- Paginação
- Contadores de likes/dislikes
- Tags
- Acesso ao post

### ✅ 4. Perfil do Usuário (/user/{id})
- Exibição completa das informações do usuário

---

## 🔍 Filtros

- Filtro por tag  
- Busca por título  
- Filtro por número de likes  

---

## ⚙️ Arquitetura e Integração com API

A aplicação consome os dados da DummyJSON utilizando um service dedicado, com Actions e Helpers.

Para popular o banco local, foi criado o comando:

```bash
php artisan dummyjson:import
```

---

## ⚙️ Instalação

### Pré-requisitos

- PHP 8.2+
- Composer
- Node 22.19
- NPM 10.9.x
- Mysql
---

### Passos

1. Clone o repositório 
2. Instale as dependências PHP: `composer install` 
3. Instale as dependências do Node: `npm install` 
4. Crie o .env usando o .env.example como base 
5. Crie a chave do app: `php artisan key:generate` 
6. (Opcional) No .env, sete senha padrão dos usuários para realizar login: `DEFAULT_PASSWORD=password` 
7. Configure o banco de dados 
8. Execute as migrations: `php artisan migrate` 
9. Rode o comando que consome a Api do DummyJSON: `php artisan dummyjson:import` 
10. Faça build dos componentes Vuejs: `npm run buid` 
11. Por fim, rode o servidor: `php artisan serve` 
12. (Opcional)Para rodar os testes: `php artisan test`

---

## 🎥 Apresentação
- Link da apresentação: [Apresentação](https://www.youtube.com/watch?v=MyzIepQw2Lg)

---
