FROM node:20

WORKDIR /var/www

# Копируем только package.json
COPY frontend/package*.json ./

# Устанавливаем зависимости
RUN npm install

# Копируем фронтенд
COPY frontend/. .

# Запуск dev сервера
CMD ["npx", "vite", "dev", "--host", "0.0.0.0"]
