const TelegramBot = require('node-telegram-bot-api');
const axios = require('axios');
const token = '995721225:AAH_sT-xly7qNvVdbu2J_nosjXjjEttfaIw';

function ban(name, admin) {
    url = "http://localhost/lab6/api/admin.php?name="+name+"&admin="+admin+"&type=ban";
    axios.get(url)
        .then((resp) => {
            switch (resp.data.type){
                case "success":
                    msg = "User is banned now";
                    bot.sendMessage(chatId, msg);
                break;
                case "error":
                    msg = "Server error";
                    bot.sendMessage(chatId, msg);
                break;
            }
        })
        .catch((error) => {
            console.log(error.data);
            msg = "Unknown error";
            bot.sendMessage(chatId, msg);
        })
}

function razban(name, admin) {
    url = "http://localhost/lab6/api/admin.php?name="+name+"&admin="+admin+"&type=razban";
    axios.get(url)
        .then((resp) => {
            switch (resp.data.type){
                case "success":
                    msg = "User is razbanned now";
                    bot.sendMessage(chatId, msg);
                break;
                case "error":
                    msg = "Server error";
                    bot.sendMessage(chatId, msg);
                break;
            }
        })
        .catch((error) => {
            console.log(error);
            msg = "Unknown error";
            bot.sendMessage(chatId, msg);
        })
}

const bot = new TelegramBot(token, {polling:true});

bot.onText(/\/help/, (msg) => {
    const chatId = msg.chat.id;
    bot.sendMessage(chatId, 
    `Доступные команды: 
    /help - помощь  
    /ban [name] [admin token] - Ban a person
    /razban [name] [admin token] - Razban a person`
    , {parse_mode: "HTML"});
});

bot.onText(/\/ban (.+) (.+)/, (msg, match) => {
    chatId = msg.chat.id;
    ban(match[1], match[2]);
});

bot.onText(/\/razban (.+) (.+)/, (msg, match) => {
    chatId = msg.chat.id;
    razban(match[1], match[2]);
});
