# 🎶 AzuraCast.Webhook-Processor
AzuraCast's built-in Webhook integration is limited and does not allow full customization.  
**AzuraCast.Webhook-Processor** acts as a **translation layer**, giving you **complete control** over how webhook messages are sent to **Discord**.

## ✨ Features
- ✅ **Customizable Discord Webhook Output** – Modify formatting and content.
- ✅ **Artist & DJ Detection** – Displays both when applicable or defaults to **Unknown Artist**.
- ✅ **Listener Stats** – Shows **unique & total listeners** with a join link.
- ✅ **Dynamic Timezone Support** – Automatically accounts for Daylight Savings.
- ✅ **Rate Limiting Protection** – Prevents excessive webhook requests.

---

## 📥 Installation

### 1️⃣ Clone or Download the Repository
```
git clone https://github.com/Hailey-Ross/AzuraCast.Webhook-Processor.git
cd AzuraCast.Webhook-Processor
```

### 2️⃣ Configure the .env File
###### ⚠ Do not share this file! Keep it private.
Create a .env file to store API keys and webhook settings.  
Example:
```
AZURACAST_WEBHOOK_KEY=your-secure-key
DISCORD_WEBHOOK_URL=https://discord.com/api/webhooks/your-webhook-url
```
  
### 3️⃣ Deploy & Connect to AzuraCast
Place AzuraCast_Webhook.php on your web server.  
Set AzuraCast's Webhook URL to:
```
https://yourdomain.com/AzuraCast_Webhook.php?key=your-secure-key
```


### Preview "As is"
![image](https://github.com/user-attachments/assets/4ec16d04-81e7-4c76-8d82-8e3a9fcc5a5c)
