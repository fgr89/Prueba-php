# Usa una imagen base de Node para construir la aplicación
FROM node:18 AS builder

# Establecer el directorio de trabajo
WORKDIR /app

# Copiar el archivo package.json y package-lock.json
COPY package*.json ./

# Instalar dependencias (incluyendo Angular CLI)
RUN npm install -g @angular/cli
RUN npm install

# Copiar el resto de la aplicación al contenedor
COPY . .

# Construir la aplicación Angular
RUN npm run build --prod

# Etapa de producción usando Nginx
FROM nginx:alpine

# Copiar la aplicación construida desde la etapa anterior al servidor Nginx
COPY --from=builder /app/dist/product-management /usr/share/nginx/html

# Exponer el puerto 80 para el acceso HTTP
EXPOSE 80

# Comando para iniciar Nginx
CMD ["nginx", "-g", "daemon off;"]