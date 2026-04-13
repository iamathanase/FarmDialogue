# FarmDialogue - Agile & Scrum Documentation

## Project Overview

**Project Name:** FarmDialogue  
**Version:** 1.0.0  
**Start Date:** March 2026  
**Status:** Active Development (Sprint 1)  
**Team Size:** 4 members

## Scrum Framework

### Team Roles

- **Scrum Master:** [Team Member 1] - Facilitates ceremonies, removes blockers
- **Product Owner:** [Team Member 2] - Manages backlog, sets priorities
- **Developer 1:** [Team Member 3] - Backend & Database
- **Developer 2:** [Team Member 4] - Frontend & UI/UX

### Sprint Schedule

- **Sprint Duration:** 2 weeks
- **Daily Standup:** 9:00 AM (15 minutes)
- **Sprint Planning:** Start of sprint (2 hours)
- **Sprint Review:** End of sprint (1.5 hours)
- **Sprint Retrospective:** End of sprint (1 hour)

## User Stories (Backlog)

### Sprint 1: Foundation & Authentication (Weeks 1-2)

#### US-001: User Registration
**As a** new user  
**I want to** create an account  
**So that** I can access the FarmDialogue platform

- **Acceptance Criteria:**
  - User can enter first name, last name, email, phone number
  - User can select role (Farmer, Customer, Vendor, Gardener)
  - User can set password
  - System validates email format and password strength
  - Confirmation email is sent after registration
  - User receives success message

- **Estimated Points:** 5
- **Priority:** HIGH
- **Status:** In Development

#### US-002: User Login
**As a** registered user  
**I want to** log in with my email and password  
**So that** I can access my account

- **Acceptance Criteria:**
  - User can enter email and password
  - System validates credentials
  - User receives authentication token
  - User is redirected to dashboard
  - Invalid credentials show error message

- **Estimated Points:** 3
- **Priority:** HIGH
- **Status:** In Development

#### US-003: View Products
**As a** customer  
**I want to** browse available products  
**So that** I can find fresh produce

- **Acceptance Criteria:**
  - Products are displayed in a grid
  - Products show name, price, seller info
  - User can filter by category
  - Search functionality works
  - Products show availability status

- **Estimated Points:** 5
- **Priority:** HIGH
- **Status:** In Design

#### US-004: Create Product Listing
**As a** farmer/vendor  
**I want to** create a product listing  
**So that** I can sell my products

- **Acceptance Criteria:**
  - Farmer can enter product details
  - Product image can be uploaded
  - Price and quantity can be set
  - Category can be selected
  - Listing is published immediately

- **Estimated Points:** 8
- **Priority:** HIGH
- **Status:** Backlog

### Sprint 2: Marketplace & Orders (Weeks 3-4)

#### US-005: Place Order
**As a** customer  
**I want to** place an order  
**So that** I can buy products

- **Acceptance Criteria:**
  - User can add products to cart
  - User can review order before purchase
  - Payment method selection available
  - Order confirmation sent via email
  - Order appears in user dashboard

- **Estimated Points:** 8
- **Priority:** HIGH
- **Status:** Backlog

#### US-006: Track Order
**As a** customer  
**I want to** track my order status  
**So that** I know when it will arrive

- **Acceptance Criteria:**
  - User can see order status
  - Order timeline is displayed
  - User receives notifications on status change
  - Seller can update order status
  - Delivery address is displayed

- **Estimated Points:** 5
- **Priority:** MEDIUM
- **Status:** Backlog

### Sprint 3: Communication & Learning (Weeks 5-6)

#### US-007: Message Users
**As a** community member  
**I want to** send messages to other users  
**So that** I can communicate about products

- **Acceptance Criteria:**
  - User can send direct messages
  - Messages are stored in database
  - User can view message history
  - Unread messages are indicated
  - User receives notifications

- **Estimated Points:** 5
- **Priority:** MEDIUM
- **Status:** Backlog

#### US-008: Access Learning Resources
**As a** gardening enthusiast  
**I want to** access learning materials  
**So that** I can learn about gardening

- **Acceptance Criteria:**
  - Learning hub displays articles, tutorials, videos
  - Resources are categorized
  - Users can filter by type
  - Resources are searchable
  - User can save favorite resources

- **Estimated Points:** 8
- **Priority:** MEDIUM
- **Status:** Backlog

## Definition of Done (DoD)

A user story is considered "Done" when:

- [ ] Code is written and reviewed
- [ ] Unit tests pass (>80% coverage)
- [ ] Integration tests pass
- [ ] Code is merged to develop branch
- [ ] Documentation is updated
- [ ] Design review completed
- [ ] No critical bugs found
- [ ] Performance acceptable
- [ ] Accessibility standards met (WCAG 2.1 AA)

## Sprint Metrics

### Sprint 1 Metrics

| Metric | Target | Actual |
|--------|--------|--------|
| Velocity | 13 points | - |
| Burn-down | On track | - |
| Code coverage | 80% | - |
| Bug found | <5 | - |

## Backlog Refinement

### Product Backlog

```
Sprint 1 (13 pts)
├── US-001: User Registration (5pts) - Complete
├── US-002: User Login (3pts) - In Progress  
├── US-003: View Products (5pts) - In Design

Sprint 2 (21 pts)
├── US-004: Create Product Listing (8pts)
├── US-005: Place Order (8pts)
├── US-006: Track Order (5pts)

Sprint 3 (13 pts)
├── US-007: Message Users (5pts)
├── US-008: Learning Resources (8pts)

Future Sprints
├── Vendor verification system
├── Reviews and ratings
├── Payment integration
├── Admin dashboard
├── Advanced search
├── Mobile optimization
```

## Development Process

### Git Workflow

```
main (production)
  ↓
develop (staging)
  ↓
feature/user-authentication
feature/product-listing
feature/order-system
bugfix/login-issue
```

### Commit Message Convention

```
[TYPE][ISSUE] Short description

TYPE: feat, fix, docs, style, refactor, test
ISSUE: User story ID (e.g., US-001)

Example:
feat[US-001] Add user registration form
```

## Quality Assurance

### Testing Strategy

- **Unit Tests:** Individual functions and methods
- **Integration Tests:** API endpoints and database
- **System Tests:** Complete user flows
- **UAT:** User acceptance testing

### Code Review Checklist

- [ ] Code follows style guide
- [ ] No console errors
- [ ] Tests pass
- [ ] No breaking changes
- [ ] Documentation updated
- [ ] Performance acceptable

## Risks & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|-----------|
| Database performance issues | Medium | High | Implement caching, optimize queries |
| Authentication breaches | Low | Critical | Use JWT, implement rate limiting |
| Scope creep | High | Medium | Stick to user stories, manage changes |
| Team member unavailability | Low | Medium | Cross-functional knowledge sharing |

## Communication Plan

- **Daily Standup:** 9:00 AM via Teams
- **Sprint Planning:** Every 2 weeks (2 hours)
- **Retrospectives:** Sprint end (Thursdays)
- **Status Updates:** Weekly email to stakeholders
- **Emergency Contact:** Project Manager

## Success Criteria

By end of Sprint 3:
- ✅ Authentication system working
- ✅ Product marketplace functional
- ✅ Order system implemented
- ✅ Messaging system operational
- ✅ Learning hub accessible
- ✅ 80%+ test coverage
- ✅ Zero critical bugs
- ✅ Performance baseline established

## Next Phases (Post-MVP)

1. **Phase 2:** Admin Dashboard & Analytics
2. **Phase 3:** Payment Integration
3. **Phase 4:** Mobile App Development
4. **Phase 5:** Advanced Features (AI recommendations, etc.)

---

**Last Updated:** March 24, 2026  
**Next Review:** Sprint Planning (March 31, 2026)
