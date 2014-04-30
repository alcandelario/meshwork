Meshwork
========

A resource management and networking tool for Chicago's dispersed and under-represented community organizations

This mvp is designed to help unite Chicago's vast network of small to medium-sized organizations, in order to facilitate easier communication, knowledge and resource sharing among citizens and other community orgs. 

It can be used as a simple phonebook to help organizations keep in touch, a resource map for anyone interested in finding organizations and services, and a public calendar that shows info about upcoming events throughout the city.


DEMO hosted at: fydasite.com/meshwork

user: guest@user.com
 pwd: password

Implementation
==============
- Server-side MVC application (CodeIgniter) 

- Some JQuery to handle UI (i.e. the date-picker when creating a new event)

- MySQL used as the data store


Outstanding Issues (amongst many)
==================
- When creating new events, the mechanism for "inviting" other Meshwork users is not going to scale
- Entering new users could be aided by perhaps importing a spreadsheet
- Retrieving an event manually deleted from the Google Calendar results in an unhandled Google API error


Credits
=======
The idea was inspired by the Data/Docs hackathon, sponsored by the Tribeca Film institute and University of Chicago. Filmmakers were paired with designers/programmers and given 3 days to create some thing which represents the spirit of the documentary. 

The app is inspired by a documentary surrounding the lack of communication and resources in low-income neighborhoods during an intense heatwave in Chicago which killed over 700 people in 1995. 
