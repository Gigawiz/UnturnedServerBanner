using Rocket.API;
using Rocket.API.Collections;
using Rocket.Core.Logging;
using Rocket.Core.Plugins;
using Rocket.Unturned;
using Rocket.Unturned.Chat;
using Rocket.Unturned.Player;
using SDG.Unturned;
using System;
using System.Collections.Generic;
using System.IO;
using System.Net;
using System.Security.Cryptography;
using System.Text;
using System.Timers;
using UnityEngine;

namespace UnturnedServerBanner
{
    public class UnturnedServerBannerConfiguration : IRocketPluginConfiguration
    {
        //Konfigurační soubor
        public string ApiKey;
        public string ServerBannerAddress;

        public void LoadDefaults()
        {
            ApiKey = "MyAPIKey";
            ServerBannerAddress = "http://www.yourbannersite.com";
        }
    }

    public class UnturnedServerBanner : RocketPlugin<UnturnedServerBannerConfiguration>
    {
        //Define some variables
        public static UnturnedServerBanner Instance;
        public string ApiKey;
        public bool shutdown = false;
        public int curplayers = 0;
        public string serverName = SDG.Unturned.Provider.serverName;
        public string map = SDG.Unturned.Provider.map;
        public string maxplayers = SDG.Unturned.Provider.maxPlayers.ToString();
        public string PvP = SDG.Unturned.Provider.PvP.ToString();
        public string ipAddy = "0.0.0.0"; //GetPublicIP();

        protected override void Load()
        {
            ApiKey = Configuration.Instance.ApiKey;

            //Add players to the current online count when you load the plugin
            foreach (SDG.Unturned.SteamPlayer plr in SDG.Unturned.Provider.Players)
            {
                curplayers++;
            }

            //Adding players to the current online count when they connect to the server
            U.Events.OnPlayerConnected += (UnturnedPlayer player) =>
            {
                curplayers++;
                UpdateServer();
            };
            //Removing players from the worksheet when disconnected from the server
            U.Events.OnPlayerDisconnected += (UnturnedPlayer player) =>
            {
                curplayers = curplayers - 1;
                UpdateServer();
            };
            //clear players on shutdown
            U.Events.OnShutdown += () =>
            {
                shutdown = true;
                UpdateServer();
            };
            UpdateServer();
        }

        public static string GetPublicIP()
        {
            string externalip = new WebClient().DownloadString("http://icanhazip.com");
            return externalip;
        }

        private void UpdateServer()
        {
            //Sends data to the server
            string url = Configuration.Instance.ServerBannerAddress + "/update_server.php?name=" + serverName + "&ip=" + ipAddy + "&map=" + map + "&curplayers=" + curplayers + "&maxplayers=" + maxplayers + "&gamemode=" + PvP + "&serverstatus=Online&api_key=" + ApiKey;
            if (shutdown)
            {
                url = Configuration.Instance.ServerBannerAddress + "/update_server.php?name=" + serverName + "&ip=" + ipAddy + "&map=" + map + "&curplayers=" + curplayers + "&maxplayers=" + maxplayers + "&gamemode=" + PvP + "&serverstatus=Offline&api_key=" + ApiKey;
            }
            string result = new WebClient().DownloadString(url);
            
            //De-Activates the plugin if API Key is wrong
            if (result == "Invalid API Key Supplied!")
            {
                Logger.LogError("API Key on server does not match the one provided in UnturnedServerBanner.configuration.xml!");
                Logger.LogError("Unloading plugin!");
            }
            else
            {
                Logger.Log(result);
            }
        }
    }
}
