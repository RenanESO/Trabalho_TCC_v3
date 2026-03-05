<p align="center">
  <img src="https://capsule-render.vercel.app/api?type=slice&color=0:1a1a2e,100:16213e&height=250&section=header&text=Renan%20Evilásio&fontSize=70&fontAlignY=35&animation=fadeIn&fontColor=fff&desc=Desenvolvedor%20ERP%20|%20Delphi%20|%20Python%20|%20Laravel&descAlignY=60&descSize=20&descColor=a0c4ff" />
</p>

<p align="center">
  <a href="https://www.linkedin.com/in/renan-evil%C3%A1sio-43b357247">
    <img src="https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white" />
  </a>
  <a href="https://github.com/RenanESO">
    <img src="https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white" />
  </a>
  <a href="https://www.beecrowd.com.br/judge/pt/profile/483370">
    <img src="https://img.shields.io/badge/Beecrowd-1E90FF?style=for-the-badge&logo=codeforces&logoColor=white" />
  </a>
  <img src="https://img.shields.io/badge/status-disponível-brightgreen?style=for-the-badge" />
</p>

# 📷 FotoPlus - Organizador de Fotos com Reconhecimento Facial (TCC)

## 📋 Sobre o Projeto

O **FotoPlus** é uma aplicação web desenvolvida como Trabalho de Conclusão de Curso (TCC). O objetivo do sistema é auxiliar na organização de coleções de fotos pessoais utilizando técnicas de Inteligência Artificial, especificamente **Reconhecimento Facial**.

O sistema permite que o usuário:
1.  **Conecte-se ao Google Drive** para baixar suas fotos.
2.  **Treine o sistema** identificando pessoas em fotos (face tagging).
3.  **Organize automaticamente** as fotos baixadas, agrupando-as em pastas baseadas nas pessoas identificadas.
4.  **Detecte duplicidade** de imagens para economizar espaço.

> **Nota:** Este projeto trabalha em conjunto com o repositório que utiliza Python para IA do projeto: [Trabalho_TCC_Python_v3 FotoPlus](https://github.com/seu-usuario/Trabalho_TCC_Python_v3).

## 🚀 Tecnologias Utilizadas

### Backend (PHP)
-   **Framework**: [Laravel 11.x](https://laravel.com)
-   **Linguagem**: PHP 8.2+
-   **Autenticação**: Laravel Jetstream (com Livewire) & Laravel Sanctum
-   **Integração Google**: Google API Client (`google/apiclient`)
-   **Banco de Dados**: SQLite (padrão), compatível com MySQL/PostgreSQL.

### Frontend
-   **Framework**: [Livewire 3.x](https://livewire.laravel.com)
-   **Estilização**: [Tailwind CSS](https://tailwindcss.com)
-   **Build Tool**: Vite

### Inteligência Artificial (Python)
O núcleo de processamento de imagens e reconhecimento facial é escrito em Python.
-   **Linguagem**: Python 3.x
-   **Bibliotecas Principais**:
    -   `face_recognition` / `dlib`: Para detecção e reconhecimento de faces.
    -   `opencv-python` (cv2): Para manipulação de imagens.
    -   `numpy`: Para operações numéricas e manipulação de arrays (descritores faciais).
    -   `python-dotenv`: Para gerenciamento de variáveis de ambiente.

---

## ⚙️ Pré-requisitos

Para rodar este projeto, você precisará ter instalado em sua máquina:

1.  **PHP 8.2+** (com extensões `sqlite`, `curl`, `fileinfo`, `gd`, `openssl`, `mbstring`).
2.  **Composer** (Gerenciador de dependências do PHP).
3.  **Node.js & NPM** (Para compilar os assets do frontend).
4.  **Python 3.x** (Para os scripts de reconhecimento facial).
5.  **Git** (Opcional, para versionamento).

---

## 🛠️ Instalação e Configuração

Siga os passos abaixo para configurar o ambiente de desenvolvimento.

### 1. Clonar e Configurar o Backend (Laravel)

Navegue até a pasta do projeto Laravel (`FotoPlus`):

```bash
cd FotoPlus
```

Instale as dependências do PHP:

```bash
composer install
```

Copie o arquivo de configuração de ambiente:

```bash
cp .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

Crie o banco de dados (SQLite) e execute as migrações:

> **Nota**: Se estiver usando Windows e não tiver o arquivo `database/database.sqlite`, crie um arquivo vazio com esse nome antes de rodar o comando abaixo, ou aceite a criação automática se o Laravel perguntar.

```bash
php artisan migrate
```

Crie o link simbólico para o Storage (Essencial para acessar as fotos):

```bash
php artisan storage:link
```

### 2. Configurar o Frontend

Instale as dependências do Node.js:

```bash
npm install
```

Compile os assets (CSS/JS):

```bash
npm run build
```
*(Para desenvolvimento com hot-reload, use `npm run dev` em um terminal separado)*

### 3. Configurar o Ambiente Python (IA)

O sistema utiliza scripts Python localizados em `storage/app/public/deteccao/`.

Navegue até a pasta dos scripts:

```bash
cd storage/app/public/deteccao
```

Recomenda-se criar um ambiente virtual (venv):

```bash
python -m venv venv
# Windows
.\venv\Scripts\activate
# Linux/Mac
source venv/bin/activate
```

Instale as dependências necessárias usando o arquivo `requirements.txt` incluído:

```bash
pip install -r requirements.txt
```

> **Atenção**: A instalação do `dlib` pode exigir compiladores C++ (Visual Studio Build Tools no Windows ou `build-essential` no Linux). Se tiver dificuldades, procure por rodas (wheels) pré-compiladas do dlib para sua versão do Python.

#### Compilação do Executável (Importante)

O código PHP (`App\Livewire\Training.php`, etc.) está configurado para chamar um **executável** Python chamado `principal` (ou `principal.exe` no Windows) localizado na pasta `dist/` dentro de `deteccao`.

Você precisa gerar esse executável usando o **PyInstaller**:

1.  Instale o PyInstaller:
    ```bash
    pip install pyinstaller
    ```

2.  Gere o executável:
    ```bash
    pyinstaller --onefile principal.py
    ```

Isso criará uma pasta `dist` contendo o arquivo `principal` (ou `principal.exe`). O Laravel irá chamar este arquivo.

> **Nota para Windows**: Se o Laravel não conseguir encontrar o executável, verifique se o código PHP está apontando para o nome correto do arquivo (incluindo `.exe` se necessário). O arquivo `principal.py` já importa as dependências locais, então o PyInstaller deve empacotar tudo corretamente.

> **Alternativa para Desenvolvedores**: Se preferir não compilar, você precisará alterar o código PHP (nos arquivos dentro de `app/Livewire/`) para chamar `python principal.py` ao invés do executável compilado.

### 4. Configuração do Google Drive

Para que a integração com o Google Drive funcione:

1.  Crie um projeto no [Google Cloud Console](https://console.cloud.google.com/).
2.  Habilite a **Google Drive API**.
3.  Crie credenciais de **OAuth 2.0 Client ID**.
4.  Baixe o arquivo JSON das credenciais.
5.  O código atual (`App\Services\GoogleService.php`) procura por um arquivo JSON específico em `storage/app/`.
    *   Verifique o nome do arquivo esperado no código (`GoogleService.php`) ou renomeie seu arquivo baixado e atualize o código para corresponder.

---

## ▶️ Como Rodar

1.  Inicie o servidor Laravel:
    ```bash
    php artisan serve
    ```

2.  Acesse `http://localhost:8000` no seu navegador.

3.  Crie uma conta (Register) ou faça login.

4.  No Dashboard, você poderá:
    *   Navegar até **Treinamento** para cadastrar pessoas e suas fotos.
    *   Usar **Organizar** para processar suas fotos.
    *   Verificar logs de processamento.

---

## 📂 Estrutura de Pastas Importantes

*   `app/Livewire/`: Lógica dos componentes de interface (Treinamento, Organização, etc.).
*   `app/Services/`: Lógica de integração com serviços externos (Google Drive).
*   `app/Models/`: Modelos de dados (User, Person, Face, etc.).
*   `storage/app/public/deteccao/`: Scripts Python de reconhecimento facial.
    *   `principal.py`: Ponto de entrada principal.
    *   `treinamento.py`: Lógica de treinamento.
    *   `organiza.py`: Lógica de organização.
    *   `dataset/`: Modelos pré-treinados do dlib (`shape_predictor`, `resnet_model`, etc.) devem estar aqui. **Verifique se estes arquivos existem!**

## ⚠️ Observações Importantes

*   **Modelos do Dlib**: O sistema precisa dos arquivos `.dat` do dlib (`shape_predictor_68_face_landmarks.dat`, `dlib_face_recognition_resnet_model_v1.dat`, etc.) dentro de `storage/app/public/deteccao/dataset/`. Se não estiverem lá, você precisará baixá-los (estão disponíveis publicamente na internet).
*   **Permissões**: Certifique-se de que a pasta `storage` e `bootstrap/cache` tenham permissões de escrita.
