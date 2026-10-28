import argparse
import ipaddress
import json
import socket
import sys
import time
from concurrent.futures import ThreadPoolExecutor
from datetime import datetime

MAX_PUERTOS = 5000


def a_entero(texto):
    try:
        return int(texto)
    except ValueError:
        raise ValueError("Formato de puertos invalido: " + texto)


def parsear_puertos(especificacion):
    puertos = set()

    for parte in especificacion.split(","):
        parte = parte.strip()
        if not parte:
            continue

        if "-" in parte:
            desde, hasta = parte.split("-", 1)
            desde, hasta = a_entero(desde), a_entero(hasta)
            if desde > hasta:
                raise ValueError("Rango de puertos invalido: " + parte)
            rango = range(desde, hasta + 1)
        else:
            rango = [a_entero(parte)]

        for puerto in rango:
            if puerto < 1 or puerto > 65535:
                raise ValueError("Puerto fuera de rango (1-65535): " + str(puerto))
            puertos.add(puerto)
            if len(puertos) > MAX_PUERTOS:
                raise ValueError("Maximo " + str(MAX_PUERTOS) + " puertos por escaneo")

    if not puertos:
        raise ValueError("No se indicaron puertos")

    return sorted(puertos)


def resolver_host(host):
    try:
        ip = ipaddress.ip_address(socket.gethostbyname(host))
    except (socket.gaierror, UnicodeError):
        raise ValueError("No se pudo resolver el host: " + host)

    if not (ip.is_loopback or ip.is_private):
        raise ValueError("Solo se pueden escanear equipos propios: localhost o redes privadas")

    return str(ip)


def puerto_abierto(ip, puerto, timeout):
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as conexion:
        conexion.settimeout(timeout)
        try:
            conexion.connect((ip, puerto))
            return True
        except OSError:
            return False


def nombre_servicio(puerto):
    try:
        return socket.getservbyport(puerto, "tcp")
    except OSError:
        return "desconocido"


def escanear(host, especificacion, timeout, hilos):
    puertos = parsear_puertos(especificacion)
    ip = resolver_host(host)

    inicio = time.time()
    with ThreadPoolExecutor(max_workers=hilos) as pool:
        resultados = list(pool.map(lambda p: (p, puerto_abierto(ip, p, timeout)), puertos))

    abiertos = [
        {"puerto": puerto, "servicio": nombre_servicio(puerto)}
        for puerto, abierto in resultados
        if abierto
    ]

    return {
        "host": host,
        "ip": ip,
        "inicio": datetime.fromtimestamp(inicio).strftime("%Y-%m-%d %H:%M:%S"),
        "duracion_ms": round((time.time() - inicio) * 1000),
        "escaneados": len(puertos),
        "abiertos": len(abiertos),
        "no_abiertos": len(puertos) - len(abiertos),
        "puertos": abiertos,
    }


def main():
    parser = argparse.ArgumentParser(description="Escaner de puertos TCP para equipos propios")
    parser.add_argument("--host", default="127.0.0.1")
    parser.add_argument("--ports", default="1-1024")
    parser.add_argument("--timeout", type=float, default=0.5)
    parser.add_argument("--workers", type=int, default=200)
    args = parser.parse_args()

    sys.stdout.reconfigure(encoding="utf-8")

    try:
        if not 0.05 <= args.timeout <= 5:
            raise ValueError("El timeout debe estar entre 0.05 y 5 segundos")
        resultado = escanear(args.host, args.ports, args.timeout, max(1, min(args.workers, 500)))
    except ValueError as error:
        print(json.dumps({"error": str(error)}, ensure_ascii=False))
        return 2

    print(json.dumps(resultado, ensure_ascii=False))
    return 0


if __name__ == "__main__":
    sys.exit(main())
