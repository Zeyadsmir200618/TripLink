const hotelsByCity = {
      Egypt: ["Cairo Grand Hotel", "Nile View Resort", "Pyramids Palace"],
      Saudi: ["Riyadh Crown", "Jeddah Sea View", "Desert Pearl Hotel"],
      Dubai: ["Burj Luxury Suites", "Palm Resort", "Downtown Royal"]
    };

    function updateHotels() {
      const city = document.getElementById("city").value;
      const hotelSelect = document.getElementById("hotel_name");
      hotelSelect.innerHTML = '<option value="">-- Select Hotel --</option>';

      if (hotelsByCity[city]) {
        hotelsByCity[city].forEach(hotel => {
          const option = document.createElement("option");
          option.value = hotel;
          option.textContent = hotel;
          hotelSelect.appendChild(option);
        });
      }
    }
  
