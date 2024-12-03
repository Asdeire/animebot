# Practical Work №10: Developing a Chatbot

## **Topic:**  
Developing a chatbot.

## **Objective:**  
Learn to work with the REST API of a third-party chatbot service.

---

## **Short Theoretical Background**  
This practical work focuses on utilizing Laravel to develop a Telegram chatbot. Laravel provides powerful tools for managing APIs, while the chatbot will interact with the Kitsu API to fetch data about anime. It will mimic the core functionalities of "MyAnimeList."

For more details, refer to the official [Laravel documentation](https://laravel.com/docs).

---

## **Task Instructions**  

1. **Use Laravel Framework.**  
   Set up and configure a Laravel project for the chatbot development.  

2. **Develop a Telegram Chatbot.**  
   Use a library such as [Telegraph](https://github.com/def-studio/telegraph) to simplify Telegram Bot API integration.

3. **Create a 'MyAnimeList' Analog.**  
   Implement the following features for the chatbot:  

   - **3.1. View the list of all anime.**  
     Retrieve and display anime data from the Kitsu API.  

   - **3.2. Filter Anime.**  
     Allow filtering by specific attributes (e.g., genre, rating, popularity).  

   - **3.3. Search by Title or Other Attributes.**  
     Enable users to search anime by title or other attributes such as release year, type, or score.  

   - **3.4. View Detailed Anime Information.**  
     Provide a detailed description of the selected anime, including its title, synopsis, genre, and ratings.  

   - **3.5. Add to a Favorites List with a Rating.**  
     Let users add anime to a favorites list and assign a rating (similar to MyAnimeList).  

   - **3.6. *(Optional)* Add Watch Status Flags.**  
     Include flags such as "Watching," "Completed," "Dropped," etc., to categorize anime in the user's list.  

   - **3.7. *(Optional)* Inline Mode Support.**  
     Implement Telegram's inline mode for seamless interaction without opening chat windows.

4. **Use the Kitsu API as the Data Source.**  
   Refer to the [Kitsu API Documentation](https://kitsu.docs.apiary.io/#introduction/authentication/refreshing-an-access-token) for details on how to authenticate and fetch anime data.  

5. **Integrate with Telegram Bot API.**  
   Choose a library from the [Telegram Bot API libraries list](https://core.telegram.org/bots/samples). The recommended library is [Telegraph](https://github.com/def-studio/telegraph).

---

## **Technologies Used**  
- **Backend Framework:** Laravel  
- **Chatbot Library:** Telegraph
- **External API:** Kitsu API  

---

## **Steps to Complete the Task**

1. **Project Setup:**  
   - Create a new Laravel project:
     ```bash
     laravel new chatbot
     cd chatbot
     composer require defstudio/telegraph
     ```
   - Configure the .env file with the necessary Telegram Bot token.

2. **Connect to the Kitsu API:**  
   - Use Laravel's HTTP Client (`Http`) to fetch anime data from the Kitsu API.  
   - Ensure you handle API authentication and pagination effectively.

3. **Bot Command and Interaction Development:**  
   - Define bot commands for viewing anime lists, searching, filtering, and adding to favorites.  
   - Use Telegraph's message handling to interact with users.

4. **Create the Features:**  
   - Implement the core functionality (view list, filter, search, detailed view, etc.).  
   - Extend functionality with optional watch status flags and inline mode if desired.  

5. **Testing:**  
   - Test the bot locally and ensure it responds to user commands correctly.  
   - Deploy the bot and test it in a Telegram chat.

---

## **Installation**

1. Clone the repository:  
   ```bash
   git clone https://github.com/Asdeire/animebot.git
   cd chatbot
   ```

2. Install dependencies:  
   ```bash
   composer install
   ```

3. Set up the .env file with your Telegram Bot token and Kitsu API credentials.

4. Run the application:  
   ```bash
   php artisan serve
   ```

---

## **Usage**

1. Add your bot to a Telegram chat.  
2. Start the bot and use the commands to interact (e.g., `/list`, `/search`, `/add`, etc.).  

---

## **Contributing**

Contributions are welcome! Please fork the repository, make your changes, and submit a pull request.

---

## **License**

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
