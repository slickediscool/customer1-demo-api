# eFlow Demo API

This is a simple PHP API mimicking basic functionality for a demo

## Endpoints

- GET /health - Health check
- GET /api/messages - List messages
- POST /api/messages - Receive a message
- POST /api/chat - Simple chat response

## Docker Setup

To build and run the Docker container:

1. Build the image:
   docker build -t eflow-demo-api .

2. Run the container:
   docker run -p 80:80 eflow-demo-api

The API will be available at http://localhost
Test commit to trigger GitHub Actions
