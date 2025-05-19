# 🧭 Journey Sorter API (PHP 8.1+)

A lightweight internal PHP API to sort unordered boarding passes into a complete, step-by-step travel itinerary.  
Built using modern PHP features: `readonly`, `constructor property promotion`, and `enum`.

---

## ✈️ Features

- Sorts unordered travel steps (boarding cards) into the correct journey order
- Outputs clean, human-readable travel instructions
- Uses modern PHP 8.1 features (`enum`, `readonly`, promoted constructor properties)
- API-style interface usable from other PHP systems or HTTP

---

## 🚀 Getting Started

### 📦 Requirements

- PHP **8.1+**
- A local web server (Apache, Nginx or PHP built-in server)

### 📁 Project structure

├── index.php # Entry point (router)
<br>
├── ApiHandler.php # API logic
<br>
├── JourneySorter.php # Sorting and journey description
<br>
├── BoardingCard.php # Data model (readonly)
<br>
├── TransportType.php # Enum: train, bus, flight
<br>
├── authorized.json # Optional IP/url whitelist

### 🛠 Installation

Clone the repository, place files in your server directory.

Start a local PHP server if needed:
```bash
php -S localhost:8000
```

Access the API at:
```bash
http://localhost:8000/api/journey
```

🔍 Example Output
```json
{
  "status": "SUCCESS",
  "message": "Your itinerary has been successfully sorted.",
  "data": {
    "sorted_journey": [
      {
        "from": "Madrid",
        "to": "Barcelona",
        ...
      }
    ],
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

🔒 Security (optional)

You may restrict API access using the authorized.json file:
```json
{
  "allowed_ips": ["192.168.1.15"],
  "allowed_urls": ["https://yourdomain.com"]
}
```

✅ Tech stack

PHP 8.1+

No framework (can be integrated into Symfony, Laravel, Slim...)

📄 License

MIT — Free to use, modify, and distribute.

✉️ Contact

Developed by [Blache Nolwenn](https://www.beyondnexus.fr)
