# Emotional Support Chatroom

This project is a real-time **Emotional Support Chatroom** built with a modern web stack and deployed on an **Azure Virtual Machine** using **Docker Compose**.  
It integrates:

- **WordPress** — front-end website & chatroom UI  
- **Node.js API** — backend API for handling chatroom logic  
- **Nginx** — reverse proxy & unified entry point  
- **InfluxDB (optional)** — for storing real-time IoT or chat analytics  
- **Docker Compose** — to orchestrate all services

---

## Features

- Modern multi-service architecture  
- Front-end powered by WordPress  
- Custom backend API using Node.js + Express  
- Nginx reverse proxy routing traffic to each service  
- Containerized deployment (easy to start/stop/update)  
- Designed to run reliably on Azure VM  

---

## System Architecture
```bash
User
↓
Nginx Reverse Proxy
↓
├── WordPress (Frontend)
├── Node.js API (Backend)
└── InfluxDB (Database, optional)
```

All services run inside Docker containers and are managed through Docker Compose.

---

## Project Structure
```bash
ES-chatroom/
├── docker-compose.yml
├── api/
│ ├── index.js
│ ├── package.json
│ └── Dockerfile
├── nginx.conf
└── wordpress/
```

---

## Technologies Used

- **Azure Virtual Machine (Ubuntu)**  
- **Docker & Docker Compose**  
- **WordPress**  
- **Node.js**  
- **Nginx**  
- **InfluxDB (optional)**  

---

## ▶ How to Run (on Azure VM)

### 1. Make sure Docker is installed
```bash
docker --version
docker-compose --version
```
2. Start all services
```bash
docker-compose up -d
```

3. Stop the services
```bash
docker-compose down
```

---

### 2. Environment Variables
Create a .env file (not pushed to GitHub):

```bash
API_PORT=3000
INFLUX_USER=admin
INFLUX_PASSWORD=your_password
INFLUX_URL=http://influxdb:8086
INFLUX_TOKEN=your_token
INFLUX_ORG=your_org
INFLUX_BUCKET=your_bucket
```

---

### 3. Deployment Workflow
1. Modify project files
2. Commit changes
3. Push to GitHub
4. Pull updates from GitHub in Azure VM (optional workflow)
5. Restart Docker Compose

Example:
```bash
git add .
git commit -m "Update"
git push
```

---

## Future Improvements

- Add WebSocket-based live chat
- Add authentication
- Add HTTPS via Let's Encrypt

---

## License
MIT License.

## Author
Zephyr Tai
Project development & deployment on Azure VM.
