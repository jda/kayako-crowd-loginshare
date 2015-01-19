package main

import (
	"errors"
	"log"
	"os"
	"strings"
)

type config struct {
	Port                    string // yes, is properly a int but we always use it as a str so w/e
	CrowdAppName            string
	CrowdAppPass            string
	CrowdBaseURL            string
	KayakoForceTeam         string
	KayakoAllowedInterfaces []string
}

func loadConfig() error {
	inError := false

	cfg.Port = os.Getenv("PORT")
	if cfg.Port == "" {
		cfg.Port = "8080"
		log.Printf("config warning: PORT not set, assuming :%s\n", cfg.Port)
	}

	cfg.CrowdAppName = os.Getenv("CROWD_APPNAME")
	if cfg.CrowdAppName == "" {
		inError = true
		log.Println("config error: CROWD_APPNAME not set")
	}

	cfg.CrowdAppPass = os.Getenv("CROWD_APPPASS")
	if cfg.CrowdAppPass == "" {
		inError = true
		log.Println("config error: CROWD_APPPASS not set")
	}

	cfg.CrowdBaseURL = os.Getenv("CROWD_BASEURL")
	if cfg.CrowdBaseURL == "" {
		inError = true
		log.Println("config error: CROWD_BASEURL not set")
	}

	cfg.KayakoForceTeam = os.Getenv("KAYAKO_FORCE_TEAM")
	if cfg.KayakoForceTeam == "" {
		log.Println("config note: KAYAKO_FORCE_TEAM not set")
	}

	tmpAllowedInterfaces := os.Getenv("KAYAKO_ALLOWED_INTERFACES")
	if tmpAllowedInterfaces == "" {
		log.Println("config warning: KAYAKO_ALLOWED_INTERFACES is not set")
	} else {
		if strings.Contains(tmpAllowedInterfaces, ",") {
			cfg.KayakoAllowedInterfaces = strings.Split(tmpAllowedInterfaces, ",")
		} else {
			cfg.KayakoAllowedInterfaces = append(cfg.KayakoAllowedInterfaces, tmpAllowedInterfaces)
		}
	}

	if inError {
		return errors.New("config env incomplete")
	}

	return nil
}
