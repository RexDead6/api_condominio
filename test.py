import requests
import random

names = ["Luis", "Antonio", "Jose", "Manuel", "Ricardo", "Rodrigo"]
lastnames = ["Ricaute", "Rodriguez", "Gonzales", "Baloa", "Garcia"]

#urlBase = "http://condominio.alwaysdata.net"
urlBase = "http://20.0.2.118:8000"
for i in range(1001, 2000):
    body = {
	    "cedUsu":"27863"+str(i),
	    "nomUsu":names[random.randrange(0, 5)],
	    "apeUsu":lastnames[random.randrange(0, 4)],
	    "telUsu":"04129988083",
	    "generoUsu":"Maculino",
	    "claveUsu":"luis"
    }
    r = requests.post(url=urlBase+"/usuario", json=body).json()
    if r["status"]:
        print("000000{} inserted\n".format(i))
        r1 = requests.post(url=urlBase+"/relacionFamilia", headers={"Authorization": r["data"]["token"]}, json={"hash": "384c0c8ce1f7cb52d5e92f02db8047e1e88a150a"})
        print("updated: {}".format(r1.json()["status"]))
    else:
        print("ERROR: {}".format(r['message']))