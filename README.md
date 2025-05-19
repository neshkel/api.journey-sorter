# 🧭 Journey Sorter API (PHP 8.1+)

A lightweight internal PHP API to sort unordered boarding passes into a complete, step-by-step travel itinerary.  
Built using modern PHP features: `readonly`, `constructor property promotion`, and `enum`.

---

## ✈️ Features

- ✅ Sorts unordered travel steps (boarding cards)
- ✅ Outputs clean, human-readable itinerary
- ✅ Uses PHP 8.2+ features (`readonly`, `enum`, `constructor promotion`)
- ✅ Secured access via IP / referer whitelist
- ✅ Simple internal API design — no framework needed

---

## 📦 Requirements

- PHP 8.2 or higher
- Apache or Nginx web server (or `php -S`)
- `.htaccess` routing enabled (for Apache)

---

## 📁 Project structure

├── index.php # Entry point (router)
<br>
├── ApiHandler.php # API logic
<br>
├── JourneySorter.php # Sorting and journey description
<br>
├── BoardingCard.php # Readonly travel step data model
<br>
├── TransportType.php # Enum: train, bus, flight
<br>
├── authorized.json # IP/url whitelist (optional)
<br>
├── .htaccess # (If using Apache)

---

## 🛠 Setup

1. Clone or upload the files to your server.
2. Make sure your server runs PHP 8.2+.
3. Access the API at:
[https://www.yourdomain.com/api/journey](https://www.yourdomain.com/api/journey)
> Or locally via `php -S localhost:8000`

---

## 🔐 IP/URL Authorization

If `authorized.json` exists, only matching IPs or HTTP referers will be allowed.

```json
{
  "allowed_ips": ["193.253.182.95"],
  "allowed_urls": ["https://www.api.transport.beyondnexus.fr"]
}
```

If the file does not exist, no restriction is applied.

Unauthorized access will return:

```json
{
  "status": "FORBIDDEN",
  "message": "Access denied.",
  "hint": "Your IP or referer is not authorized to use this API."
}
```

---

## 🔍 API Usage

---

### 🏠 Root Path (`/`)

When accessing the base URL of the API (e.g. `http://localhost/` or `https://api.yourdomain.com/`),  
you'll be presented with a **HTML help page** describing:

- Available endpoints (e.g. `/api/journey`)
- Example cURL request
- Explanation of the JSON structure
- Authorization method

This serves as a lightweight API documentation homepage.

### 📥 Endpoint

```bash
GET /api/journey
```

### 📤 Example Output

```json
{
  "status": "SUCCESS",
  "message": "Your itinerary has been successfully sorted.",
  "data": {
    "sorted_journey": [ ... ],
    "final_result": [
      "Take train 78A from Madrid to Barcelona. Sit in seat 45B.",
      "...",
      "You have arrived at your destination."
    ],
    "hint": {
      "total_stops": 4,
      "start_location": "Madrid",
      "end_location": "New York JFK"
    }
  }
}
```

### 🧪 Testing

Locally via browser:

```bash
http://localhost:8000/api/journey
```

Via cURL:

```bash
curl -X GET http://localhost:8000/api/journey
```

---

## 📄 License

MIT — Use, modify, share freely.


## 👨‍💻 Developed by

[BLACHE Nolwenn / BeyondNexusTech](https://www.beyondnexus.fr)
