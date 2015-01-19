package main

import (
	"github.com/jda/go-crowd"
	"github.com/jda/go-loginshare"
)

func authHandler(lr loginshare.RequestParams) (loginshare.Response, error) {
	lsr := loginshare.Response{}

	client, err := crowd.New(
		cfg.CrowdAppName,
		cfg.CrowdAppPass,
		cfg.CrowdBaseURL,
	)
	if err != nil {
		return lsr, err
	}

	user, err := client.Authenticate(lr.Username, lr.Password)
	if err != nil {
		return lsr, err
	}

	lsr.StaffRecord.Firstname = user.FirstName
	lsr.StaffRecord.Lastname = user.LastName
	lsr.StaffRecord.Email = user.Email
	lsr.Result = 1

	if cfg.KayakoForceTeam != "" {
		lsr.StaffRecord.Team = cfg.KayakoForceTeam
	}

	return lsr, nil
}
