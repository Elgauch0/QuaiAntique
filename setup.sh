#!/usr/bin/env bash


#Verify if docker is installed
if command -v docker &> /dev/null; then
    echo "✅ Docker est installé. Procédons avec la configuration..."
else
    echo "❌ Docker n'est pas installé. Veuillez installer Docker et réessayer."
    exit 1
fi

# copy .env.example to .env
if [[ ! -f .env ]]; then
    echo ".env file does not exist. Creating from .env.example..."
    cp .env.example .env
else
    echo ".env file already exists. Skipping creation."
fi
# Lancer les conteneurs Docker en arrière-plan avec reconstruction
echo "Lancement des conteneurs Docker..."
docker compose up -d 
if [[ $? -ne 0 ]]; then
    echo "Échec du lancement des conteneurs Docker. Veuillez vérifier les erreurs ci-dessus."
    exit 1
fi


