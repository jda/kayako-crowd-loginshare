// Package loginshare provides methods for interacting with Kayako's Loginshare authentication
// system.
package loginshare

import (
	"encoding/xml"
	"errors"
	"log"
	"net/http"
)

type LoginShare struct {
	AllowedInterfaces []string
	OnNewAuth         func(r RequestParams) (Response, error)
}

type RequestParams struct {
	Username  string
	Password  string
	IPAddress string
	Interface string
}

// Response to a loginshare authentication request
type Response struct {
	XMLName     struct{} `xml:"loginshare"`
	Result      int      `xml:"result"`
	Message     string   `xml:"message,omitempty"`
	StaffRecord Staff    `xml:"staff,omitempty"`
}

// Staff record component of a loginshare response
type Staff struct {
	Firstname string `xml:"firstname"`
	Lastname  string `xml:"lastname"`
	Title     string `xml:"designation"`
	Email     string `xml:"email"`
	Cell      string `xml:"mobilenumber"`
	Signature string `xml:"signature"`
	Team      string `xml:"team"`
}

func (l *LoginShare) interfaceAllowed(ri string) bool {
	for _, ai := range l.AllowedInterfaces {
		if ri == ai {
			return true
		}
	}

	return false
}

// Request implements the http.Handler interface to provide a drop-in Loginshare
// request/response handler
func (l *LoginShare) Request(w http.ResponseWriter, r *http.Request) {
	lsr, err := l.validateRequest(r)
	if err != nil {
		w.WriteHeader(http.StatusBadRequest)
		log.Printf("invalid loginshare request from %s", r.RemoteAddr)
		return
	}

	if l.interfaceAllowed(lsr.Interface) == false {
		w.WriteHeader(http.StatusForbidden)
		log.Printf("loginshare request from %s for unpermitted interface", r.RemoteAddr)
		return
	}

	w.Header().Add("Content-Type", "application/xml")
	xw := xml.NewEncoder(w)
	resp, err := l.OnNewAuth(lsr)
	if err != nil {
		log.Printf("loginshare request from %s failed: %s", r.RemoteAddr, err)
		resp.StaffRecord = Staff{} // make sure we don't give up any info if partially populated
		resp.Result = 0
		resp.Message = err.Error()
		xw.Encode(resp)
		return
	}

	xw.Encode(resp)
	return
}

func (l *LoginShare) validateRequest(r *http.Request) (RequestParams, error) {
	var lsr RequestParams
	inError := false

	lsr.Username = r.FormValue("username")
	if lsr.Username == "" {
		inError = true
	}

	lsr.Password = r.FormValue("password")
	if lsr.Password == "" {
		inError = true
	}

	lsr.IPAddress = r.FormValue("ipaddress")
	if lsr.IPAddress == "" {
		inError = true
	}

	lsr.Interface = r.FormValue("interface")

	if inError {
		return lsr, errors.New("request incomplete")
	}

	return lsr, nil
}
