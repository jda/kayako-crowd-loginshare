package main

import (
	"github.com/gorilla/mux"
	"github.com/jda/go-loginshare"
	"log"
	"net/http"
)

var cfg config

func main() {
	err := loadConfig()
	if err != nil {
		log.Panic(err)
	}

	ls := loginshare.LoginShare{
		cfg.KayakoAllowedInterfaces,
		authHandler,
	}

	r := mux.NewRouter()
	r.HandleFunc("/endpoint", ls.Request).Methods("POST")
	http.Handle("/", r)

	log.Printf("listening on port %s...\n", cfg.Port)
	err = http.ListenAndServe(":"+cfg.Port, nil)
	if err != nil {
		log.Panic(err)
	}
}
