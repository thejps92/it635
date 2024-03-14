# PCPartCatalog - IT 635 Project

PCPartCatalog is a project built for IT 635 (Database Administration) that establishes a basic Apache web interface built with PHP, allowing the user to interact with a PostgreSQL database based around PC parts. Within the web interface, users can search the database and perform updates, including inserting new entries and deleting existing ones.

> [!NOTE]
> This project and its contents are based on running Docker Desktop in a Windows environment.

## Requirements

- **Git** installed. ([Install](https://git-scm.com/download/win))
- **Docker Desktop** installed. ([Install](https://docs.docker.com/desktop/install/windows-install/))

## Build

1. Ensure that Docker Desktop is running.
2. Open a Windows PowerShell terminal.

> [!IMPORTANT]
> Make sure to **Run as administrator**.

3. Change to your desired directory to download the files for this project with the following command:
```
cd "C:\path\to\your\directory"
```

> [!IMPORTANT]
> Make sure you are in a directory that you have write permissions for (e.g. **C:\Users\\\<Username>**, where **\<Username>** is your Windows username.)

4. Clone this GitHub repository to the working directory of your local machine with the following command:
```
git clone https://github.com/thejps92/it635.git
```

5. Move into the **it635** folder with the following command:
```
cd it635
```

6. Start the Docker containers with the following command:
```
docker-compose up
```

7. The Docker containers should now be building; once they are finished, you can access the web interface to interact with the database by opening a web browser on the host machine running the Docker containers and typing the following in the address bar: **http://127.0.0.1:8080**