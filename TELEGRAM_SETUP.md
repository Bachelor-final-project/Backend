# Telegram Bot Setup Instructions

## 1. Environment Configuration

Add these variables to your `.env` file:

```env
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_WEBHOOK_URL="${APP_URL}/telegram/webhook"
```

## 2. Run Migrations

```bash
php artisan migrate
```

## 3. Set Webhook (Production)

Use a tool like ngrok for local development or set up your production webhook:

```bash
curl -X POST "https://api.telegram.org/bot{YOUR_BOT_TOKEN}/setWebhook" \
     -H "Content-Type: application/json" \
     -d '{"url": "https://yourdomain.com/telegram/webhook"}'
```

## 4. Bot Commands

Users can interact with your bot using:

- `/start` or `/login` - Start the login process
- `/profile` - View profile (when authenticated)
- `/help` - Show available commands
- `/logout` - Unlink Telegram account

## 5. Login Flow

1. User sends `/login` to bot
2. Bot asks for email
3. User provides email
4. Bot asks for password
5. User provides password
6. Bot authenticates and links account
7. Future messages auto-authenticate via chat_id

## 6. Features

- Auto-authentication via telegram_chat_id
- Clean conversation state management
- Secure password handling
- Profile viewing
- Account unlinking
- Error handling and validation

## 7. Testing

1. Create a Telegram bot via @BotFather
2. Get the bot token
3. Add token to .env
4. Set up webhook (use ngrok for local testing)
5. Start conversation with your bot