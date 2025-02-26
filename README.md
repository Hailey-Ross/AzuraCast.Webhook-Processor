# 🎶 hails.Webhook-Processor
Azuracast's built-in webhook integration is limited and lacks basic customization.  
**hails.Webhook-Processor** acts as a **translation layer**, giving you **full control** over webhook messages sent to **Discord**.

---

## ✨ Features at a Glance
| Feature                      | Description |
|------------------------------|------------|
| 🎨 **Customizable Webhook**  | Full control over Discord message formatting. |
| 🎤 **Artist & DJ Detection**  | Displays artist and/or DJ, defaults to **Unknown Artist** when needed. |
| 👥 **Live Listener Stats**   | Shows **unique & total listeners** with a join link. |
| ⏳ **Dynamic Timezones**      | Automatically adjusts for Daylight Savings. |
| 🔒 **Rate Limiting Protection** | Prevents excessive webhook requests. |

---

## 🛑 Requirements
- Webserver
- Azuracast Station
- Discord Server Webhook
- Customization
  - very minor JSON/PHP knowledge

---

## 📥 Installation

### 1️⃣ Clone or Download the Repository
```sh
git clone https://github.com/Hailey-Ross/hails.Webhook-Processor.git
cd hails.Webhook-Processor
```

### 2️⃣ Configure the .env File
###### 🔒 Keep this file private! Do not share it. 
Create a .env file to store API keys and webhook settings.  
This file should not be accessible to anyone on the web or in your webserver.  
```sh
AZURACAST_WEBHOOK_KEY=your-secure-key
DISCORD_WEBHOOK_URL=https://discord.com/api/webhooks/your-webhook-url
```

### 3️⃣ Deploy & Connect to AzuraCast
###### 🛑 DO NOT use the default your-secure-key value, if you have. Go back to the previous step. Seriously.
Upload AzuraCast_Webhook.php to your web server.
Set AzuraCast's Webhook URL to:

```sh
https://yourdomain.com/AzuraCord.php?key=your-secure-key
```

---

## 🎨 Webhook Message Preview  
🎵 **Live DJ Mode**  
![image](https://github.com/user-attachments/assets/4ec16d04-81e7-4c76-8d82-8e3a9fcc5a5c)

🎶 **Auto-DJ Mode**  
![image](https://github.com/user-attachments/assets/7a6d547e-7b03-4b10-8816-892660cb7570)

---

## 💡 Want to tweak the format?
Check out `AzuraCord.php` for easy adjustments.  
You *could* further customize:  
| Customization Type             | Examples |
|------------------------------|------------|
| Webhook appearance | color, fields, icons |  
| Message structure | song titles, artist info |  
| Fallback Images | Album Art, Footer, User Avatar |
| Embed styling | verbage, formatting |  


## ❤️ Support & Contributions  
Contributions, feedback, and feature requests are welcome!  
Feel free to submit an issue or pull request.  
