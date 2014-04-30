Meshwork
========

A resource management and networking tool for Chicago's dispersed and under-represented community organizations

This tool is an MVP designed to help unite Chicago's vast network of small to medium-sized organizations communicate and collaborate much easier than before. It's goal is to share events amongst other community leaders and their populations. 

It can be used as a simple phonebook to help organizations keep in touch, a resource map for anyone interested in finding organizations with a certain mission and a public facing calendar that collects information about upcoming events throughout the city.


Implementation
==============
Server-side MVC application (CodeIgniter) 

Some JQuery to handle UI (i.e. the date-picker when creating a new event)

MySQL database


Outstanding Issues (amongst many)
==================
- When creating new events, the mechanism for "inviting" other Meshwork users is not going to scale well
- Entering new users could be aided by perhaps importing a spreadsheet
- Retrieving an event manually deleted from the Google Calendar results in an unhandled Google API error
